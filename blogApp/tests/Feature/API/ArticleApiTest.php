<?php

namespace Tests\Feature\API;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $article;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->article = Article::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);
    }

    public function test_can_get_all_articles()
    {
        Article::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'category', 'user', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    public function test_can_get_single_article()
    {
        $response = $this->getJson("/api/v1/articles/{$this->article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->article->id,
                    'title' => $this->article->title,
                    'content' => $this->article->content,
                ]
            ]);
    }

    public function test_auth_user_can_create_article()
    {
        Sanctum::actingAs($this->user);

        $articleData = [
            'title' => 'New Test Article',
            'content' => 'This is a test article content',
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/v1/articles', $articleData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Test Article',
                    'content' => 'This is a test article content',
                ]
            ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'New Test Article',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_auth_user_can_update_own_article()
    {
        Sanctum::actingAs($this->user);

        $updatedData = [
            'title' => 'Updated Article Title',
            'content' => 'Updated content for the article',
            'category_id' => $this->category->id,
        ];

        $response = $this->putJson("/api/v1/articles/{$this->article->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->article->id,
                    'title' => 'Updated Article Title',
                    'content' => 'Updated content for the article',
                ]
            ]);

        $this->assertDatabaseHas('articles', [
            'id' => $this->article->id,
            'title' => 'Updated Article Title',
        ]);
    }

    public function test_auth_user_cannot_update_others_article()
    {
        $anotherUser = User::factory()->create();
        Sanctum::actingAs($anotherUser);

        $updatedData = [
            'title' => 'Updated Article Title',
            'content' => 'Updated content for the article',
            'category_id' => $this->category->id,
        ];

        $response = $this->putJson("/api/v1/articles/{$this->article->id}", $updatedData);

        $response->assertStatus(403);
    }

    public function test_auth_user_can_delete_own_article()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/articles/{$this->article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Article deleted successfully'
            ]);

        $this->assertDatabaseMissing('articles', [
            'id' => $this->article->id
        ]);
    }

    public function test_auth_user_cannot_delete_others_article()
    {
        $anotherUser = User::factory()->create();
        Sanctum::actingAs($anotherUser);

        $response = $this->deleteJson("/api/v1/articles/{$this->article->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('articles', [
            'id' => $this->article->id
        ]);
    }

    public function test_unauthenticated_user_cannot_create_article()
    {
        $articleData = [
            'title' => 'New Test Article',
            'content' => 'This is a test article content',
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/v1/articles', $articleData);

        $response->assertStatus(401);
    }
}
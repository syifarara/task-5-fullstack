<?php

namespace Tests\Feature\API;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $adminUser;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->adminUser = User::factory()->create([
            'is_admin' => true
        ]);
        $this->category = Category::factory()->create();
    }

    public function test_can_get_all_categories()
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_can_get_single_category()
    {
        $response = $this->getJson("/api/v1/categories/{$this->category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ]
            ]);
    }

    public function test_admin_can_create_category()
    {
        Sanctum::actingAs($this->adminUser);

        $categoryData = [
            'name' => 'New Test Category',
        ];

        $response = $this->postJson('/api/v1/categories', $categoryData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'New Test Category',
                    'slug' => 'new-test-category',
                ]
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'New Test Category',
            'slug' => 'new-test-category',
        ]);
    }

    public function test_non_admin_cannot_create_category()
    {
        Sanctum::actingAs($this->user);

        $categoryData = [
            'name' => 'New Test Category',
        ];

        $response = $this->postJson('/api/v1/categories', $categoryData);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_category()
    {
        Sanctum::actingAs($this->adminUser);

        $updatedData = [
            'name' => 'Updated Category Name',
        ];

        $response = $this->putJson("/api/v1/categories/{$this->category->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->category->id,
                    'name' => 'Updated Category Name',
                    'slug' => 'updated-category-name',
                ]
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id,
            'name' => 'Updated Category Name',
            'slug' => 'updated-category-name',
        ]);
    }

    public function test_non_admin_cannot_update_category()
    {
        Sanctum::actingAs($this->user);

        $updatedData = [
            'name' => 'Updated Category Name',
        ];

        $response = $this->putJson("/api/v1/categories/{$this->category->id}", $updatedData);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_category()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->deleteJson("/api/v1/categories/{$this->category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category deleted successfully'
            ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $this->category->id
        ]);
    }

    public function test_non_admin_cannot_delete_category()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/categories/{$this->category->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id
        ]);
    }

    public function test_can_get_category_with_articles()
    {
        // Create articles for this category
        $articles = \App\Models\Article::factory()->count(3)->create([
            'category_id' => $this->category->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson("/api/v1/categories/{$this->category->id}/articles");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'category', 'user', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    public function test_unauthenticated_user_cannot_create_category()
    {
        $categoryData = [
            'name' => 'New Test Category',
        ];

        $response = $this->postJson('/api/v1/categories', $categoryData);

        $response->assertStatus(401);
    }
}
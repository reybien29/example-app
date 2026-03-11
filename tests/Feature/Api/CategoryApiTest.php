<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('api_integrations.internal.token', 'internal-secret-token');
});

it('returns paginated categories', function (): void {
    /** @var \Tests\TestCase $this */
    Category::factory()->count(2)->create();

    $this
        ->withToken('internal-secret-token')
        ->getJson('/api/v1/internal/categories')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('creates a category', function (): void {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/categories', [
            'user_id' => $user->id,
            'name' => 'Clients',
        ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Clients')
        ->assertJsonPath('data.user_id', $user->id);

    $this->assertDatabaseHas('categories', ['name' => 'Clients', 'user_id' => $user->id]);
});

it('shows a single category', function (): void {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create(['name' => 'Family']);

    $this
        ->withToken('internal-secret-token')
        ->getJson('/api/v1/internal/categories/'.$category->id)
        ->assertOk()
        ->assertJsonPath('data.name', 'Family')
        ->assertJsonPath('data.id', $category->id);
});

it('updates a category', function (): void {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create(['name' => 'Old Name']);

    $this
        ->withToken('internal-secret-token')
        ->patchJson('/api/v1/internal/categories/'.$category->id, [
            'name' => 'New Name',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'New Name');

    $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
});

it('deletes a category', function (): void {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();

    $this
        ->withToken('internal-secret-token')
        ->deleteJson('/api/v1/internal/categories/'.$category->id)
        ->assertNoContent();

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('rejects duplicate category name for same user', function (): void {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();
    Category::factory()->create(['user_id' => $user->id, 'name' => 'Work']);

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/categories', [
            'user_id' => $user->id,
            'name' => 'Work',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('allows same category name for different users', function (): void {
    /** @var \Tests\TestCase $this */
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    Category::factory()->create(['user_id' => $userA->id, 'name' => 'Work']);

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/categories', [
            'user_id' => $userB->id,
            'name' => 'Work',
        ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Work');
});

it('rejects missing name on store', function (): void {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/categories', [
            'user_id' => $user->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('rejects invalid user_id on store', function (): void {
    /** @var \Tests\TestCase $this */
    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/categories', [
            'user_id' => 'non-existent-uuid',
            'name' => 'Work',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['user_id']);
});

it('requires authentication', function (): void {
    /** @var \Tests\TestCase $this */
    $this
        ->getJson('/api/v1/internal/categories')
        ->assertUnauthorized();
});

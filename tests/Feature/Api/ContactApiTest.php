<?php

use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('api_integrations.internal.token', 'internal-secret-token');
});

it('returns paginated contacts', function (): void {
    Contact::factory()->count(2)->create();

    $this
        ->withToken('internal-secret-token')
        ->getJson('/api/v1/internal/contacts')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('creates a contact', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/contacts', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'mobile_number' => '+1234567890',
        ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Jane Doe')
        ->assertJsonPath('data.email', 'jane@example.com')
        ->assertJsonPath('data.category_id', $category->id);

    $this->assertDatabaseHas('contacts', ['name' => 'Jane Doe', 'user_id' => $user->id]);
});

it('shows a single contact', function (): void {
    $contact = Contact::factory()->create(['name' => 'John Smith']);

    $this
        ->withToken('internal-secret-token')
        ->getJson('/api/v1/internal/contacts/'.$contact->id)
        ->assertOk()
        ->assertJsonPath('data.name', 'John Smith')
        ->assertJsonPath('data.id', $contact->id);
});

it('updates a contact name', function (): void {
    $contact = Contact::factory()->create(['name' => 'Old Name']);

    $this
        ->withToken('internal-secret-token')
        ->patchJson('/api/v1/internal/contacts/'.$contact->id, [
            'name' => 'New Name',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'New Name');

    $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'name' => 'New Name']);
});

it('updates a contact category to another category owned by same user', function (): void {
    $user = User::factory()->create();
    $categoryA = Category::factory()->create(['user_id' => $user->id]);
    $categoryB = Category::factory()->create(['user_id' => $user->id]);
    $contact = Contact::factory()->create(['user_id' => $user->id, 'category_id' => $categoryA->id]);

    $this
        ->withToken('internal-secret-token')
        ->patchJson('/api/v1/internal/contacts/'.$contact->id, [
            'category_id' => $categoryB->id,
        ])
        ->assertOk()
        ->assertJsonPath('data.category_id', $categoryB->id);
});

it('deletes a contact', function (): void {
    $contact = Contact::factory()->create();

    $this
        ->withToken('internal-secret-token')
        ->deleteJson('/api/v1/internal/contacts/'.$contact->id)
        ->assertNoContent();

    $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
});

it('rejects category not owned by the contact user on store', function (): void {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $categoryForB = Category::factory()->create(['user_id' => $userB->id]);

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/contacts', [
            'user_id' => $userA->id,
            'category_id' => $categoryForB->id,
            'name' => 'Test Contact',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category_id']);
});

it('rejects category not owned by the contact user on update', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $contact = Contact::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);

    $otherUser = User::factory()->create();
    $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);

    $this
        ->withToken('internal-secret-token')
        ->patchJson('/api/v1/internal/contacts/'.$contact->id, [
            'category_id' => $otherCategory->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category_id']);
});

it('rejects missing name on store', function (): void {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/contacts', [
            'user_id' => $user->id,
            'category_id' => $category->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('rejects invalid user_id on store', function (): void {
    $category = Category::factory()->create();

    $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/contacts', [
            'user_id' => 'non-existent-uuid',
            'category_id' => $category->id,
            'name' => 'Test Contact',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['user_id']);
});

it('requires authentication', function (): void {
    $this
        ->getJson('/api/v1/internal/contacts')
        ->assertUnauthorized();
});

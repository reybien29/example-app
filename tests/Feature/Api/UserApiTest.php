<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('api_integrations.internal.token', 'internal-secret-token');
    config()->set('api_integrations.external.keys', ['partner-alpha']);
});

it('returns paginated internal users with filters', function (): void {
    User::factory()->create(['name' => 'Alpha Internal', 'email' => 'alpha@example.com']);
    User::factory()->create(['name' => 'Beta Internal', 'email' => 'beta@example.com']);

    $response = $this
        ->withToken('internal-secret-token')
        ->getJson('/api/v1/internal/users?search=alpha&per_page=10');

    $response
        ->assertOk()
        ->assertJsonPath('data.0.email', 'alpha@example.com')
        ->assertJsonCount(1, 'data');
});

it('creates users through the internal api', function (): void {
    $response = $this
        ->withToken('internal-secret-token')
        ->postJson('/api/v1/internal/users', [
            'name' => 'Internal API User',
            'email' => 'internal@example.com',
            'password' => 'secret1234',
        ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.email', 'internal@example.com');

    $user = User::query()->where('email', 'internal@example.com')->firstOrFail();

    expect(Hash::check('secret1234', $user->password))->toBeTrue();
});

it('updates and deletes users through the internal api', function (): void {
    $user = User::factory()->create();

    $this
        ->withToken('internal-secret-token')
        ->patchJson('/api/v1/internal/users/'.$user->id, [
            'name' => 'Updated Internal Name',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated Internal Name');

    $this
        ->withToken('internal-secret-token')
        ->deleteJson('/api/v1/internal/users/'.$user->id)
        ->assertNoContent();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('upserts users through the external api for integration sync', function (): void {
    $createResponse = $this
        ->withHeaders(['X-Integration-Key' => 'partner-alpha'])
        ->postJson('/api/v1/external/users/upsert', [
            'name' => 'External Partner User',
            'email' => 'external@example.com',
        ]);

    $createResponse
        ->assertCreated()
        ->assertJsonPath('meta.action', 'created')
        ->assertJsonPath('data.email', 'external@example.com');

    $updateResponse = $this
        ->withHeaders(['X-Integration-Key' => 'partner-alpha'])
        ->postJson('/api/v1/external/users/upsert', [
            'name' => 'External Partner User Updated',
            'email' => 'external@example.com',
        ]);

    $updateResponse
        ->assertOk()
        ->assertJsonPath('meta.action', 'updated')
        ->assertJsonPath('data.name', 'External Partner User Updated');
});

it('exposes a reduced payload for external user reads', function (): void {
    $user = User::factory()->create();

    $this
        ->withHeaders(['X-Integration-Key' => 'partner-alpha'])
        ->getJson('/api/v1/external/users/'.$user->id)
        ->assertOk()
        ->assertJsonPath('data.email', $user->email)
        ->assertJsonMissingPath('data.created_at')
        ->assertJsonMissingPath('data.password');
});

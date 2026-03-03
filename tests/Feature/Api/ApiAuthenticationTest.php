<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('blocks internal api requests without token', function (): void {
    config()->set('api_integrations.internal.token', 'internal-secret-token');

    $this->getJson('/api/v1/internal/users')
        ->assertUnauthorized();
});

it('allows internal api requests with valid bearer token', function (): void {
    config()->set('api_integrations.internal.token', 'internal-secret-token');
    User::factory()->create();

    $this
        ->withToken('internal-secret-token')
        ->getJson('/api/v1/internal/users')
        ->assertOk();
});

it('blocks external api requests without integration key', function (): void {
    config()->set('api_integrations.external.keys', ['partner-alpha']);

    $this->getJson('/api/v1/external/users')
        ->assertUnauthorized();
});

it('blocks external api requests with invalid integration key', function (): void {
    config()->set('api_integrations.external.keys', ['partner-alpha']);

    $this
        ->withHeaders(['X-Integration-Key' => 'partner-unknown'])
        ->getJson('/api/v1/external/users')
        ->assertUnauthorized();
});

it('allows external api requests with valid integration key', function (): void {
    config()->set('api_integrations.external.keys', ['partner-alpha']);
    User::factory()->create();

    $this
        ->withHeaders(['X-Integration-Key' => 'partner-alpha'])
        ->getJson('/api/v1/external/users')
        ->assertOk();
});

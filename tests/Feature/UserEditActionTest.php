<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the edit page for an existing user', function (): void {
    $user = User::factory()->create();

    $this
        ->get(route('user.edit', $user->id))
        ->assertOk()
        ->assertSeeText('Edit User')
        ->assertSee('value="'.$user->email.'"', false);
});

it('returns not found for edit when user does not exist', function (): void {
    $this
        ->get(route('user.edit', '00000000-0000-0000-0000-000000000000'))
        ->assertNotFound();
});

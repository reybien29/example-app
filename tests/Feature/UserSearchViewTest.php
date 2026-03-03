<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the search page without missing users layout view errors', function (): void {
    User::factory()->create([
        'name' => 'Carl Search',
        'email' => 'carl@example.com',
    ]);

    $this
        ->get(route('user.search', ['search' => 'carl']))
        ->assertOk()
        ->assertSeeText('Carl Search');
});

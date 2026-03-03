---
name: frontend
description: "Builds and maintains Laravel Blade frontend views. Activates when creating or editing Blade templates, layouts, partials, forms, or pages; working with web routes; styling with custom CSS; handling flash messages, validation errors, or pagination; or when the user mentions view, blade, template, layout, page, form, CSS, UI, or any visual/frontend change."
license: MIT
metadata:
  author: user
---

# Frontend Development

## When to Apply

Activate this skill when:

- Creating or editing Blade templates, layouts, or partials
- Adding or updating web routes and resource controllers
- Styling pages with custom CSS
- Implementing forms with CSRF protection and validation feedback
- Working with flash messages, pagination, or empty states

## Project Structure

```
resources/views/
├── welcome.blade.php          # Landing / home page
├── home.blade.php
├── layouts/
│   └── guest.blade.php        # Guest layout
└── users/
    ├── layout.blade.php       # Users section master layout
    ├── index.blade.php        # User listing with search & pagination
    ├── create.blade.php       # Create user form
    ├── edit.blade.php         # Edit user form
    └── partials/              # Reusable partials
```

## Blade Conventions

### Layouts & Sections

Use `@extends` / `@section` / `@yield` for layout inheritance:

```blade
@extends('users.layout')

@section('title', 'Page Title')

@section('content')
    {{-- page content --}}
@endsection
```

### Including Partials

```blade
@include('users.partials.form', ['user' => $user])
```

### Outputting Data

Always escape output with `{{ }}`. Use `{!! !!}` only for trusted HTML:

```blade
<p>{{ $user->name }}</p>
```

### Conditionals & Loops

```blade
@if ($condition)
    ...
@endif

@forelse ($items as $item)
    ...
@empty
    <p>No items found.</p>
@endforelse
```

## Web Routes

Web routes live in `routes/web.php`. Use resource routes for CRUD:

```php
use App\Http\Controllers\UserController;

Route::resource('user', UserController::class);
Route::get('my_search', [UserController::class, 'search'])->name('user.search');
```

### Named Routes in Blade

Always use named routes for links and redirects:

```blade
<a href="{{ route('user.index') }}">Users</a>
<a href="{{ route('user.edit', $user->id) }}">Edit</a>
```

## Forms

### CSRF & Method Spoofing

All POST/PUT/PATCH/DELETE forms must include `@csrf`. Use `@method()` for non-POST verbs:

```blade
<form action="{{ route('user.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    ...
</form>
```

### Validation Errors

Display field-level errors using `@error`:

```blade
<input type="text" name="name" value="{{ old('name') }}">
@error('name')
    <span class="error-msg">{{ $message }}</span>
@enderror
```

### Flash Messages

Check for session flash messages at the top of content sections:

```blade
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

## CSS Conventions

The project uses **custom CSS** (not Tailwind) with scoped `<style>` blocks inside Blade views.

### Design Tokens (CSS Variables)

Dark-mode pages (e.g. `welcome.blade.php`) use CSS custom properties:

```css
:root {
    --bg:       #0a0d14;
    --surface:  #111520;
    --blue:     #2563eb;
    --blue-dim: rgba(37, 99, 235, 0.18);
    --text:     #f0f4ff;
    --muted:    #6b7fa3;
    --border:   rgba(255,255,255,0.07);
}
```

Light-mode admin pages use inline values directly (e.g. `#0f1117`, `#e5e7eb`, `#9ca3af`).

### Typography

The project uses **DM Sans** (body) and **DM Serif Display** (headings) from Google Fonts:

```html
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
```

Apply in CSS:

```css
font-family: 'DM Sans', sans-serif;       /* body text */
font-family: 'DM Serif Display', serif;   /* headings */
```

### Button Patterns

```css
/* Primary action button */
.btn-primary {
    background: #2563eb;
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid transparent;
    transition: background 0.2s, transform 0.15s;
}
.btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); }

/* Secondary / ghost button */
.btn-secondary {
    background: rgba(255,255,255,0.04);
    color: #94a3b8;
    border: 1px solid rgba(255,255,255,0.07);
    padding: 12px 20px;
    border-radius: 10px;
}
```

### Card / Table Pattern

Admin pages wrap tables in a card:

```css
.table-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
```

### Animations

Use `@keyframes fadeUp` for page-entry animations:

```css
.page { animation: fadeUp 0.35s ease both; }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
```

## Pagination

Use Laravel's built-in paginator. Append query strings to preserve search state:

```blade
@if ($data->hasPages())
    <div class="pagination-wrap">
        {{ $data->appends(request()->query())->links('pagination::tailwind') }}
    </div>
@endif
```

In the controller:

```php
$data = User::orderBy('id', 'desc')->paginate(4);
```

## Resource Controllers

Generate with artisan:

```bash
php artisan make:controller UserController --resource
```

Standard method map:

| Method    | Route              | Action              |
|-----------|--------------------|---------------------|
| `index`   | GET /user          | List all users      |
| `create`  | GET /user/create   | Show create form    |
| `store`   | POST /user         | Save new user       |
| `show`    | GET /user/{id}     | Show single user    |
| `edit`    | GET /user/{id}/edit| Show edit form      |
| `update`  | PUT /user/{id}     | Save updated user   |
| `destroy` | DELETE /user/{id}  | Delete user         |

## Common Pitfalls

- Forgetting `@csrf` in forms (causes 419 errors)
- Using `{!! !!}` for user-supplied data (XSS risk)
- Hardcoding URLs instead of using `route()` helper
- Not using `old()` helper to repopulate form fields after validation failure
- Forgetting `@method('DELETE')` for delete forms
- Not appending query strings to paginator links when search is active

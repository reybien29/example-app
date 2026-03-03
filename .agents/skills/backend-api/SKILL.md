---
name: backend-api
description: "Builds and maintains Laravel REST API endpoints. Activates when creating or editing API controllers, form requests, API resources, API routes, or middleware; working with JSON responses, API versioning, authentication tokens, or rate limiting; or when the user mentions API, endpoint, REST, JSON, resource, request validation, internal API, external integration, or HTTP response."
license: MIT
metadata:
  author: user
---

# Backend API Development

## When to Apply

Activate this skill when:

- Creating or editing API controllers, form requests, or API resources
- Adding or modifying API routes with versioning
- Implementing API authentication middleware
- Working with JSON responses and HTTP status codes
- Building internal or external integration endpoints

## API Structure

The API is versioned under `api/v1` with two access tiers:

```
routes/api.php
app/Http/Controllers/Api/
└── V1/
    ├── Internal/
    │   └── UserController.php       # Full CRUD, internal token auth

    └── External/
        └── UserIntegrationController.php  # Read + upsert, integration key auth

app/Http/Requests/Api/V1/
├── Internal/
│   ├── UserIndexRequest.php
│   ├── UserStoreRequest.php
│   └── UserUpdateRequest.php
└── External/
    ├── ExternalUserIndexRequest.php
    └── ExternalUserUpsertRequest.php

app/Http/Resources/
├── UserResource.php
└── ExternalUserResource.php

app/Http/Middleware/
├── EnsureInternalApiToken.php
└── EnsureExternalIntegrationKey.php

config/api_integrations.php
```

## API Routes

Routes live in `routes/api.php`. Always use versioned prefixes and named routes:

```php
use App\Http\Controllers\Api\V1\External\UserIntegrationController;
use App\Http\Controllers\Api\V1\Internal\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {

    // Internal API — full CRUD, token-based auth
    Route::prefix('internal')
        ->middleware(['auth.internal-api', 'throttle:internal-api'])
        ->name('api.v1.internal.')
        ->group(function (): void {
            Route::apiResource('users', UserController::class);
        });

    // External integrations — limited access, integration key auth
    Route::prefix('external')
        ->middleware(['auth.external-integration', 'throttle:external-api'])
        ->name('api.v1.external.')
        ->group(function (): void {
            Route::get('users', [UserIntegrationController::class, 'index'])->name('users.index');
            Route::get('users/{user}', [UserIntegrationController::class, 'show'])->name('users.show');
            Route::post('users/upsert', [UserIntegrationController::class, 'upsert'])->name('users.upsert');
        });
});
```

## API Controllers

Generate with artisan:

```bash
php artisan make:controller Api/V1/Internal/UserController --api
```

### Internal Controller Pattern

```php
namespace App\Http\Controllers\Api\V1\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Internal\UserStoreRequest;
use App\Http\Requests\Api\V1\Internal\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $users = User::query()->orderByDesc('created_at')->paginate(15);
        return UserResource::collection($users);
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());
        return (new UserResource($user))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $user->update($request->validated());
        return new UserResource($user->fresh());
    }

    public function destroy(User $user): Response
    {
        $user->delete();
        return response()->noContent();
    }
}
```

## Form Requests

Generate with artisan:

```bash
php artisan make:request Api/V1/Internal/UserStoreRequest
```

### Request Pattern

```php
namespace App\Http\Requests\Api\V1\Internal;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
```

Always use `$request->validated()` in controllers — never `$request->all()`.

## API Resources

Generate with artisan:

```bash
php artisan make:resource UserResource
```

### Resource Pattern

```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'created_at'        => $this->created_at?->toIso8601String(),
            'updated_at'        => $this->updated_at?->toIso8601String(),
        ];
    }
}
```

- Always use `toIso8601String()` for date fields.
- Use `?->` (nullsafe operator) for nullable timestamps.
- Return resource collections with `UserResource::collection($paginator)`.

## Authentication Middleware

### Internal API (`auth.internal-api`)

Validates a bearer token against a configured secret. Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'auth.internal-api'        => EnsureInternalApiToken::class,
        'auth.external-integration' => EnsureExternalIntegrationKey::class,
    ]);
})
```

### External Integration (`auth.external-integration`)

Validates an integration key from `config/api_integrations.php`. Integration keys are stored in config, not the database.

## HTTP Response Conventions

| Scenario              | Status Code                    | Method                          |
|-----------------------|--------------------------------|---------------------------------|
| Successful GET/PUT    | 200 OK                         | `return new UserResource($user)` |
| Created resource      | 201 Created                    | `->response()->setStatusCode(201)` |
| Deleted resource      | 204 No Content                 | `return response()->noContent()` |
| Validation failure    | 422 Unprocessable Entity       | Automatic via FormRequest        |
| Unauthenticated       | 401 Unauthorized               | Thrown by middleware             |
| Not found             | 404 Not Found                  | Automatic via route model binding |

## Filtering & Pagination

Use `when()` for optional query filters to keep queries clean:

```php
$users = User::query()
    ->when(isset($validated['search']), function ($query) use ($validated): void {
        $query->where(function ($q) use ($validated): void {
            $q->where('name', 'like', '%'.$validated['search'].'%')
              ->orWhere('email', 'like', '%'.$validated['search'].'%');
        });
    })
    ->when(array_key_exists('email_verified', $validated), function ($query) use ($validated): void {
        if ((bool) $validated['email_verified']) {
            $query->whereNotNull('email_verified_at');
        } else {
            $query->whereNull('email_verified_at');
        }
    })
    ->orderByDesc('created_at')
    ->paginate($perPage)
    ->withQueryString();

return UserResource::collection($users);
```

## Rate Limiting

Rate limiters are configured in `AppServiceProvider` or `RouteServiceProvider`:

```php
RateLimiter::for('internal-api', function (Request $request) {
    return Limit::perMinute(120);
});

RateLimiter::for('external-api', function (Request $request) {
    return Limit::perMinute(60);
});
```

## Common Pitfalls

- Using `$request->all()` instead of `$request->validated()` in controllers
- Returning `200` for newly created resources instead of `201`
- Not using `response()->noContent()` for `204` responses (returning `null` instead)
- Forgetting `->withQueryString()` on paginated results
- Using `created_at->format(...)` instead of `->toIso8601String()` in resources
- Not wrapping nested `orWhere` conditions in a closure (causes incorrect SQL)
- Hardcoding auth tokens in code instead of reading from config/env
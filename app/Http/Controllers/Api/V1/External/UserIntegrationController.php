<?php

namespace App\Http\Controllers\Api\V1\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\External\ExternalUserIndexRequest;
use App\Http\Requests\Api\V1\External\ExternalUserUpsertRequest;
use App\Http\Resources\ExternalUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class UserIntegrationController extends Controller
{
    public function index(ExternalUserIndexRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 25);

        $users = User::query()
            ->select(['id', 'name', 'email', 'updated_at'])
            ->when(
                isset($validated['search']),
                function ($query) use ($validated): void {
                    $query->where(function ($innerQuery) use ($validated): void {
                        $innerQuery
                            ->where('name', 'like', '%'.$validated['search'].'%')
                            ->orWhere('email', 'like', '%'.$validated['search'].'%');
                    });
                }
            )
            ->when(
                isset($validated['updated_after']),
                fn ($query) => $query->where('updated_at', '>', $validated['updated_after'])
            )
            ->orderByDesc('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        return ExternalUserResource::collection($users);
    }

    public function show(User $user): ExternalUserResource
    {
        return new ExternalUserResource($user);
    }

    public function upsert(ExternalUserUpsertRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->firstOrNew(['email' => $validated['email']]);
        $wasRecentlyCreated = ! $user->exists;

        if ($wasRecentlyCreated && ! array_key_exists('password', $validated)) {
            $validated['password'] = Str::password(40);
        }

        $user->fill($validated);
        $user->save();

        $statusCode = $wasRecentlyCreated ? JsonResponse::HTTP_CREATED : JsonResponse::HTTP_OK;

        return (new ExternalUserResource($user->fresh()))
            ->additional([
                'meta' => [
                    'action' => $wasRecentlyCreated ? 'created' : 'updated',
                ],
            ])
            ->response()
            ->setStatusCode($statusCode);
    }
}

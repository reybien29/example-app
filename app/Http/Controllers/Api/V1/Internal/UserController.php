<?php

namespace App\Http\Controllers\Api\V1\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Internal\UserIndexRequest;
use App\Http\Requests\Api\V1\Internal\UserStoreRequest;
use App\Http\Requests\Api\V1\Internal\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(UserIndexRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 15);

        $users = User::query()
            ->select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at'])
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
                array_key_exists('email_verified', $validated),
                function ($query) use ($validated): void {
                    if ((bool) $validated['email_verified']) {
                        $query->whereNotNull('email_verified_at');
                    } else {
                        $query->whereNull('email_verified_at');
                    }
                }
            )
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

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

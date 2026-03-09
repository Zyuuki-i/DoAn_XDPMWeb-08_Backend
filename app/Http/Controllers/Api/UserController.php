<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::orderBy('id', 'asc')
            ->get()
            ->map(function (User $user) {
                return $this->transformUser($user);
            });

        return response()->json([
            'data' => $users,
        ]);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => $this->transformUser($user),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validateUser($request);

        $user = User::create($data);

        return response()->json([
            'data' => $this->transformUser($user),
        ], 201);
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $data = $this->validateUser($request, $user->id);

        $user->update($data);

        return response()->json([
            'data' => $this->transformUser($user),
        ]);
    }

    /**
     * Remove a user from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Validate incoming user data. When updating, pass the user id to
     * ignore it in unique checks.
     */
    private function validateUser(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'.($ignoreId ? ",$ignoreId" : '')],
            'password' => ['required', 'string', 'min:6'],
            'email' => ['required', 'email', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'role' => ['required', 'string', 'max:50'],
        ];

        // when updating we allow password to be omitted
        if ($ignoreId) {
            $rules['password'] = ['sometimes', 'string', 'min:6'];
        }

        return $request->validate($rules);
    }

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'password' => $user->password,
            'email' => $user->email,
            'full_name' => $user->full_name,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => $user->role,
            'created_at' => $user->created_at,
        ];
    }
}   

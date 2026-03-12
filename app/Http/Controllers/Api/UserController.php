<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::orderBy('id', 'asc')
            ->get()
            ->map(fn($user) => $this->transformUser($user));

        return response()->json([
            'data' => $users
        ]);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => $this->transformUser($user)
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateUser($request);

        // default role when the client didn't send one
        $data['role'] = $data['role'] ?? 'customer';

        $user = User::create($data);

        return response()->json([
            'data' => $this->transformUser($user)
        ], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $this->validateUser($request, $user->id);

        $user->update($data);

        return response()->json([
            'data' => $this->transformUser($user)
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    private function validateUser(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($ignoreId)
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($ignoreId)
            ],

            'password' => $ignoreId
                ? ['sometimes', 'string', 'min:6']
                : ['required', 'string', 'min:6'],

            'full_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],

            'role' => ['sometimes', Rule::in(['admin','customer'])],
        ]);
    }

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'full_name' => $user->full_name,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => $user->role,
            'created_at' => $user->created_at?->toDateTimeString(),
        ];
    }
}
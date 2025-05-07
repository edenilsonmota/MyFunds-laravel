<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function checkUser(int $user_id): JsonResponse
    {
        $user = $this->userService->findById($user_id);

        if ($user) {
            return response()->json([
                'exists' => true,
                'name' => $user->name,
            ]);
        }

        return response()->json(['exists' => false]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Jobs\CreateUserJob;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function createUsers(Request $request)
    {
        $validated = $request->validate([
            'num_users' => 'required|integer|min:1',
        ]);

        $numUsers = $validated['num_users'];

        for ($i = 1; $i <= $numUsers; $i++) {
            $randomString = Str::random(6);
            $email = $randomString . '@example.com';

            $existingUser = User::where('email', $email)->first();
            while ($existingUser) {
                $randomString = Str::random(6);
                $email = $randomString . '@example.com';
                $existingUser = User::where('email', $email)->first();
            }

            $userData = [
                'name' => 'User ' . $randomString,
                'email' => $email,
            ];

            CreateUserJob::dispatch($userData);
        }

        return response()->json(['message' => "$numUsers users created and jobs dispatched"]);
    }

    public function ping(Request $request)
    {
        $user = $request->user();
        $user->last_ping_at = now();
        $user->save();

        return response()->json(['message' => 'Ping successful']);
    }

    public function onlineUsers()
    {
        $threshold = now()->subSeconds(30);

        $users = User::whereNotNull('last_ping_at')
            ->where('last_ping_at', '>=', $threshold)
            ->get(['id', 'name', 'last_ping_at'])
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'is_online' => true,
                ];
            });

        return response()->json($users);
    }

    public function messages()
    {
        $users = [['id' => 1, 'message' => 'test'], ['id' => 2, 'message' => 'test2']];
        return response()->json($users);
    }
}

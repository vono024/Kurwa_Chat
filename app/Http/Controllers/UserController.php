<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->paginate(10);

        return view('users.search', compact('users', 'query'));
    }

    public function show(User $user)
    {
        $posts = $user->posts()->latest()->paginate(5);
        return view('users.show', compact('user', 'posts'));
    }

    public function searchAjax(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['users' => []]);
        }

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->limit(10)
            ->get();

        return response()->json(['users' => $users]);
    }
}

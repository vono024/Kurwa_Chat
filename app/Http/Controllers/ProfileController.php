<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->bio = $request->bio;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Профіль оновлено!');
    }

    public function show(User $user)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'name' => $user->name,
                'phone' => $user->phone,
                'bio' => $user->bio,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'created_at' => $user->created_at->format('d.m.Y'),
            ]);
        }

        return view('profile.show', compact('user'));
    }
}

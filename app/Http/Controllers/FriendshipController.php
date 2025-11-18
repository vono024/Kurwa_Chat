<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendshipController extends Controller
{
    public function sendRequest(User $user)
    {
        $friendship = DB::table('friendships')
            ->where('user_id', auth()->id())
            ->where('friend_id', $user->id)
            ->first();

        if (!$friendship) {
            DB::table('friendships')->insert([
                'user_id' => auth()->id(),
                'friend_id' => $user->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Запит відправлено!');
    }

    public function acceptRequest(User $user)
    {
        DB::table('friendships')
            ->where('user_id', $user->id)
            ->where('friend_id', auth()->id())
            ->update(['status' => 'accepted']);

        return back()->with('success', 'Запит прийнято!');
    }

    public function rejectRequest(User $user)
    {
        DB::table('friendships')
            ->where('user_id', $user->id)
            ->where('friend_id', auth()->id())
            ->delete();

        return back()->with('success', 'Запит відхилено!');
    }

    public function removeFriend(User $user)
    {
        DB::table('friendships')
            ->where(function ($query) use ($user) {
                $query->where('user_id', auth()->id())
                    ->where('friend_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('friend_id', auth()->id());
            })
            ->delete();

        return back()->with('success', 'Видалено з друзів!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        // Позначити повідомлення як прочитані
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'content' => $request->content,
        ]);

        return back();
    }
}

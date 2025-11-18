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

        return redirect()->route('chat.show', $user);
    }

    public function getMessages(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'auth_id' => auth()->id()
        ]);
    }

    public function getChats()
    {
        $userId = auth()->id();

        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        $conversations = $messages->groupBy(function ($message) use ($userId) {
            return $message->sender_id === $userId
                ? $message->receiver_id
                : $message->sender_id;
        })
            ->map(function ($messages) use ($userId) {
                $lastMessage = $messages->first();
                $otherUser = $lastMessage->sender_id === $userId
                    ? $lastMessage->receiver
                    : $lastMessage->sender;

                $unreadCount = $messages->where('sender_id', $otherUser->id)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();

                return [
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'avatar' => $otherUser->avatar,
                    ],
                    'last_message' => $lastMessage->content,
                    'last_message_time' => $lastMessage->created_at->toIso8601String(),
                    'is_sent' => $lastMessage->sender_id === $userId,
                    'unread_count' => $unreadCount,
                ];
            })
            ->values();

        return response()->json([
            'conversations' => $conversations
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Коментар додано!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return back()->with('success', 'Коментар видалено!');
    }
}

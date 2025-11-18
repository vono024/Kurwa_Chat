<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'comments.user')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->content = $request->content;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Публікацію створено!');
    }

    public function show(Post $post)
    {
        $post->load('user', 'comments.user');
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $post->content = $request->content;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('posts.show', $post)->with('success', 'Публікацію оновлено!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Публікацію видалено!');
    }
}

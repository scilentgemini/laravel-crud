<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $posts = Post::all();
        return view('post-create', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:300|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:1024',
            'body' => 'required|string|max:2000',
        ]);

        // image name change and upload
        if ($request->has('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/images/'), $imageName);
            $data['image'] = $imageName;
        }

        // Posting to db
        Post::create($data);
        return back()->with('success', 'Post has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('post-edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|max:300|string',
            'image' => 'sometimes|image|mimes:png,jpg,jpeg,webp|max:1024',
            'body' => 'required|string|max:2000',
        ]);

        if($request->has('image'))
        {
            //checks for old image
            $destination = 'uploads/images/'.$post->image;
            //remove old file
            if(File::exists($destination))
            {
                File::delete($destination);
            }
            //add new image
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            //move image to server
            $request->image->move(public_path('uploads/images/'), $imageName);
            //update image on server
            $data['image'] = $imageName;
        }
        //updating data of specific id
        $post->update($data);
        return redirect()->route('post.create')->with('success', 'Post has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class BlogController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Blog::withCount('likes');

        if ($user) {
            $query->withExists([
                'likes as liked_by_me' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ]);
        }

        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        if ($request->filter === 'most_liked') {
            $query->orderByDesc('likes_count');
        } elseif ($request->filter === 'latest') {
            $query->orderByDesc('created_at');
        } 

        $blogs = $query->paginate(10);

        return response()->json($blogs);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $data['image'] = $path;
            $data['image_url'] = asset('storage/' . $path);
            
        }

        $data['user_id'] = Auth::id();

        $blog = Blog::create($data);
        return response()->json([
            'message' => 'Blog updated successfully.',

            'data' => $blog->fresh()->toArray() + ['image_url' => asset('storage/' . $blog->image)],
        ], 200);
    }




    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        $blog = Blog::findOrFail($id);

        if ($request->hasFile('image')) {

            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }


            $path = $request->file('image')->store('uploads', 'public');
            $data['image'] = $path;
            $data['image_url'] = asset('storage/' . $path);
        }

        $blog->update($data);

        return response()->json([
            'message' => 'Blog updated successfully.',
            'data' => $blog->fresh()->toArray() + ['image_url' => asset('storage/' . $blog->image)],
        ], 200);
    }


    public function destroy(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json([
            'message' => 'Blog deleted successfully',
        ], 200);
    }



    public function toggleLike($id)
    {
        $user = Auth::user();

        $blog = Blog::findOrFail($id);  
        $like = $blog->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Unlike
            $like->delete();
            return response()->json(['message' => 'Blog unliked']);
        } else {
            // Like
            $blog->likes()->create([
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'Blog liked']);
        }
    }
}

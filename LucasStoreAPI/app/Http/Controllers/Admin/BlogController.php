<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function __construct(Blog $blog, BlogService $blogService)
    {
        $this->blog = $blog;
        $this->blogService = $blogService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = $this->blog->latest('created_at')->get();
        return response()->json([
            'blogs' => $blogs,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        try {
            $data = $request->validated();
            $blog = $this->blogService->create($data);
            return response()->json([
                'blog' => $blog,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $blog = $this->blog->findOrFail($id);
            return response()->json([
                'blog' => $blog,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, string $id)
    {
        try {
            $blog = $this->blog->findOrFail($id);
            $data = $request->validated();
            $data['id'] = $id;
            $blog = $this->blogService->update($data);
            return response()->json([
                'blog' => $blog,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    public function changeStatus(string $id)
    {
        try {
            $blog = $this->blog->findOrFail($id);
            if ($blog->status == Blog::BLOG_STATUS['ACTIVE']) {
                $blog->status = Blog::BLOG_STATUS['INACTIVE'];
            } else {
                $blog->status = Blog::BLOG_STATUS['ACTIVE'];
            }
            $blog->save();
            return response()->json([
                'blog' => $blog
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $blog = $this->blog->findOrFail($id);
            if ($blog->avatar != null) {
                if (Storage::disk('public')->exists($blog->avatar)) {
                    Storage::disk('public')->delete($blog->avatar);
                }
            }
            $blog->delete();
            return response()->json(['message' => config('const.message_success')], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct(Category $category, CategoryService $categoryService)
    {
        $this->category = $category;
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->category->getAndWithCache();
        return response()->json(['categories' => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $categories = $this->categoryService->create($data);
            return response()->json([
                'category' => $categories,
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
            $category = $this->category->findAndWithCache($id);
            return response()->json([
                'category' => $category
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            $category = $this->category->findOrFail($id);
            $data = $request->all();
            $data['id'] = $id;
            $data['avatar'] = $request->file('avatar');
            $data['checkAvatar'] = $request->hasFile('avatar');
            $category = $this->categoryService->update($data);
            return response()->json([
                'category' => $category,
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
            $this->category->destroy($id);
            return response('Category deleted successfully.', 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

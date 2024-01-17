<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Major_Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct(Category $category, CategoryService $categoryService, Major_Category $majorCategory)
    {
        $this->category = $category;
        $this->categoryService = $categoryService;
        $this->majorCategory = $majorCategory;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->category->getAndWithCache();
        $menu = $this->majorCategory->getAndWithCache(Major_Category::MENU_STATUS['HOT_DEFAULT']);
        return response()->json([
            'categories' => $categories,
            'menu' => $menu
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $category = $this->categoryService->create($data);
            return response()->json([
                'category' => $category,
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
            $menu = $this->majorCategory->getAndWithCache(Major_Category::MENU_STATUS['HOT_DEFAULT']);
            return response()->json([
                'category' => $category,
                'menu' => $menu,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    public function changStatus(string $id)
    {
        try {
            $category = $this->category->findOrFail($id);
            if ($category->status == Category::CATEGORY_STATUS['ACTIVE']) {
                $category->status = Category::CATEGORY_STATUS['INACTIVE'];
            } else {
                $category->status = Category::CATEGORY_STATUS['ACTIVE'];
            }
            $category->save();
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
            $data = $request->validated();
            $data['id'] = $id;
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
            $category = $this->category->findOrFail($id);
            if ($category->avatar != null) {
                if (Storage::disk('public')->exists($category->avatar)) {
                    Storage::disk('public')->delete($category->avatar);
                }
            }
            $category->delete();
            return response()->json(['message' => config('const.message_success')], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

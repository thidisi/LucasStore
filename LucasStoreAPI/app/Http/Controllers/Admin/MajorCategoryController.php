<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major_Category;
use App\Services\MajorCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MajorCategoryController extends Controller
{
    public function __construct(Major_Category $majorCategory, MajorCategoryService $majorCategoryService)
    {
        $this->majorCategory = $majorCategory;
        $this->majorCategoryService = $majorCategoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $major_categories = $this->majorCategory->get();
        return response()->json(['major_categories' => $major_categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => "required|unique:major_categories|min:2|max:255",
                'status' => 'required'
            ]);
            $major_category = $this->majorCategoryService->create($data);
            return response()->json([
                'major_category' => $major_category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $major_category = $this->majorCategory->with('category')->findOrFail($id);
            return response()->json([
                'major_category' => $major_category
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.not_content')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $major_category = $this->majorCategory->findOrFail($id);
            $data = $request->validate([
                'name' => [
                    'required',
                    'unique:major_categories,name,' . $id . ',id',
                ],
                'status' => 'required',
            ]);
            $data['id'] = $id;
            $major_category = $this->majorCategoryService->update($data);
            return response()->json([
                'major_category' => $major_category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.not_content')], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->majorCategory->destroy($id);
            return response('MajorCategory deleted successfully.', 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.not_content')], 403);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Models\Major_Category;
use App\Models\Slide;
use App\Services\MajorCategoryService;
use App\Services\SlideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function __construct(Major_Category $majorCategory, MajorCategoryService $majorCategoryService, SlideService $slideService, Slide $slide)
    {
        $this->majorCategory = $majorCategory;
        $this->majorCategoryService = $majorCategoryService;
        $this->slideService = $slideService;
        $this->slide = $slide;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        try {
            $sortOrder = Slide::SLIDE_ORDER;
            $menu = $this->majorCategory->getAndWithCache(Major_Category::MENU_STATUS['HOT_DEFAULT']);
            $slider = $this->slide->with('major_category')->latest('created_at')->get();
            return response()->json([
                'sortOrder' => $sortOrder,
                'menu' => $menu,
                'slider' => $slider
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSlideRequest $request)
    {
        try {
            $data = $request->validated();
            $slides = $this->slideService->create($data);
            return response()->json(['slides', $slides], 200);
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
            $slide = $this->slide->with('major_category')->findOrFail($id);
            $menu = $this->majorCategory->getAndWithCache(Major_Category::MENU_STATUS['HOT_DEFAULT']);
            $sortOrder = Slide::SLIDE_ORDER;
            return response()->json([
                'slide' => $slide,
                'sortOrder' => $sortOrder,
                'menu' => $menu,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSlideRequest $request, string $id)
    {
        try {
            $slide = $this->slide->findOrFail($id);
            $data = $request->validated();
            $data['id'] = $id;
            $slide = $this->slideService->update($data);
            return response()->json(['slide', $slide], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    public function changStatus(string $id)
    {
        try {
            $slide = $this->slide->findOrFail($id);
            if ($slide->status == Slide::SLIDE_STATUS['ACTIVE']) {
                $slide->status = Slide::SLIDE_STATUS['INACTIVE'];
            } else {
                $slide->status = Slide::SLIDE_STATUS['ACTIVE'];
            }
            $slide->save();
            return response()->json(['slide', $slide], 200);
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
            $slide = $this->slide->findOrFail($id);
            if ($slide->image != null) {
                if (Storage::disk('public')->exists($slide->image)) {
                    Storage::disk('public')->delete($slide->image);
                }
            }
            $slide->delete();
            return response()->json(['message' => config('const.message_success')], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

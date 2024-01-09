<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Models\Major_Category;
use App\Models\Slide;
use App\Services\MajorCategoryService;
use App\Services\SlideService;
use Illuminate\Http\Request;

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
    public function dataSlide()
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
        $data = $request->validated();
        $slides = $this->slideService->create($data);
        return response()->json(['slides', $slides], 200);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

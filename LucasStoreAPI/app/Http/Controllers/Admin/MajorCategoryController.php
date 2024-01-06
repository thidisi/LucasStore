<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major_Category;
use Illuminate\Http\Request;

class MajorCategoryController extends Controller
{
    public function __construct(Major_Category $major_category)
    {
        $this->major_category = $major_category;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $major_categories = $this->major_category->get();
        return response()->json(['major_categories' => $major_categories], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Models\Setting;
use App\Services\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(Setting $attribute, AttributeService $attributeService)
    {
        $this->attribute = $attribute;
        $this->attributeService = $attributeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $attr = $this->attributeService->getAttribute();
            $attrValue = $this->attributeService->getAttributeValue();
            return response()->json([
                'attr' => $attr,
                'attrValue' => $attrValue,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request)
    {
        try {
            $data = $request->validated();
            $attr = $this->attributeService->create($data);

            return response()->json([
                'data' => $attr,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $attribute = $this->attributeService->findAttribute($id);
            return $attribute;
            if ($attribute->parent_id !== null) {
                $attr = $this->attributeService->getAttributeValue();
            } else {
                $attr = $this->attributeService->getAttribute();
            }
            return response()->json([
                'each' => $attribute,
                'data' => $attr,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    public function changeStatus(string $id)
    {
        try {
            $attribute = $this->attribute->findOrFail($id);
            if ($attribute->status == Setting::SETTING_STATUS['ACTIVE']) {
                $attribute->status = Setting::SETTING_STATUS['INACTIVE'];
            } else {
                $attribute->status = Setting::SETTING_STATUS['ACTIVE'];
            }
            $attribute->save();
            return response()->json(['data' => $attribute], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, string $id)
    {
        try {
            $attr = $this->attribute->findOrFail($id);
            $data = $request->validated();
            $data['id'] = $id;
            $attr = $this->attributeService->update($data);
            return response()->json([
                'data' => $attr,
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
            $this->attribute->destroy($id);
            return;
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

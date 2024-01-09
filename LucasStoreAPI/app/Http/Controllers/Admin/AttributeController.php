<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(Attribute $attribute, AttributeValue $attributeValue, AttributeService $attributeService)
    {
        $this->attribute = $attribute;
        $this->attributeValue = $attributeValue;
        $this->attributeService = $attributeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $attr = $this->attribute->latest('created_at')->get();
            $attrValue = $this->attributeValue->with('attribute')->latest('created_at')->get();
            return response()->json([
                'attr' => $attr,
                'attrValue' => $attrValue,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function dataAttr()
    {
        try {
            $attr_replace = $this->attribute->whereNull('replace_id')->get();
            $attr = $this->attribute->whereNull('replace_id')
                ->with('replace')
                ->get();
            return response()->json([
                'attr_replace' => $attr_replace,
                'attr' => $attr,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeWithAttr(Request $request)
    {
        try {
            $data = $request->validated();
            $attr = $this->attributeService->create($data);

            return response()->json([
                'attr' => $attr,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeWithAttrValue(Request $request)
    {
        try {
            $data = $request->validated();
            $attr = $this->attributeService->createWithAttrValue($data);

            return response()->json([
                'attr' => $attr,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateWithAttr(Request $request, string $id)
    {
        try {
            $attr = $this->attribute->findOrFail($id);
            $data = $request->validated();
            $attr = $this->attributeService->update($data);
            return response()->json([
                'attr' => $attr,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateWithAttrValue(Request $request, string $id)
    {
        try {
            $attrValue = $this->attributeValue->findOrFail($id);
            $data = $request->validated();
            $attrValue = $this->attributeService->updateWithAttrValue($data);
            return response()->json([
                'attrValue' => $attrValue,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyWithAttr(string $id)
    {
        try {
            $this->attribute->destroy($id);
            return;
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyWithAttrValue(string $id)
    {
        try {
            $this->attributeValue->destroy($id);
            return;
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

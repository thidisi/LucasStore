<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class AttributeService
 * @package App\Services
 */
class AttributeService
{

    public function __construct(Setting $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute()
    {
        $attributes = $this->attribute->with(['children'])->whereNull('parent_id')->latest('created_at')->get();

        $filteredAttributes = $attributes->map(function ($attribute) {
            $filteredAttribute = collect($attribute->toArray())->except(['production_id', 'created_at', 'updated_at', 'deleted_at', 'image_path', 'price_type', 'parent_id'])->all();

            $filteredChildren = $attribute->children->map(function ($child) {
                return collect($child->toArray())->except(['production_id', 'created_at', 'updated_at', 'deleted_at', 'image_path'])->all();
            });

            $attribute->setRawAttributes($filteredAttribute);
            $attribute->setRelation('children', $filteredChildren);

            return $attribute;
        });

        return $filteredAttributes;
    }

    public function getAttributeValue()
    {
        $attrValues = $this->attribute->with(['parent'])->whereNotNull('parent_id')->latest('created_at')->get();

        $filteredAttrValues = $attrValues->map(function ($attrValue) {
            $filteredAttrValue = collect($attrValue->toArray())->except(['production_id', 'created_at', 'updated_at', 'deleted_at', 'image_path'])->all();

            $filteredParent = collect($attrValue->parent->toArray())->except(['production_id', 'created_at', 'updated_at', 'deleted_at', 'image_path', 'price_type', 'parent_id'])->all();

            $attrValue->setRawAttributes($filteredAttrValue);
            $attrValue->setRelation('parent', new Setting($filteredParent));

            return $attrValue;
        });
        return $filteredAttrValues;
    }

    public function findAttribute(string $id)
    {
        $result = $this->attribute->with(['children', 'parent'])->findOrFail($id);

        if ($result) {
            $filteredResult = collect($result->toArray())->except(['production_id', 'created_at', 'updated_at', 'deleted_at', 'image_path'])->all();

            $filteredChildren = $result->children->map(function ($child) {
                return collect($child->toArray())->except(['production_id', 'created_at', 'updated_at', 'deleted_at', 'image_path'])->all();
            });

            if ($result->parent) {
                $filteredParent = collect($result->parent->toArray())->except(['production_id', 'created_at', 'updated_at', 'deleted_at', 'image_path', 'price_type', 'parent_id'])->all();
            } else {
                $filteredParent = [];
            }

            $result->setRelation('parent', new Setting($filteredParent));
            $result->setRawAttributes($filteredResult);
            $result->setRelation('children', $filteredChildren);
            if (empty($result->parent)) {
                $result->makeHidden(['parent']);
            }
            if (empty($result->children)) {
                $result->makeHidden(['children']);
            }
        }
        return $result;
    }

    /**
     * @param array $params
     * @return Setting
     * @throws Throwable
     */
    public function create(array $params = []): Setting
    {
        DB::beginTransaction();
        try {
            $attribute = $this->createByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $attribute;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function createByParams($params): Setting
    {
        $attribute = $this->attribute->create($params);
        return $attribute;
    }

    /**
     * @param array $params
     * @return Setting
     * @throws Throwable
     */
    public function update(array $params = []): Setting
    {
        DB::beginTransaction();
        try {
            $attribute = $this->updateByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $attribute;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateByParams($params): Setting
    {
        $attribute = $this->attribute->findOrFail($params['id']);
        $attribute->update($params);
        return $attribute;
    }
}

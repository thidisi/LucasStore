<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class AttributeService
 * @package App\Services
 */
class AttributeService
{

    public function __construct(Attribute $attribute, AttributeValue $attributeValue)
    {
        $this->attribute = $attribute;
        $this->attributeValue = $attributeValue;
    }

    /**
     * @param array $params
     * @return Attribute
     * @throws Throwable
     */
    public function create(array $params = []): Attribute
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
    private function createByParams($params): Attribute
    {
        $attribute = $this->attribute->create($params);
        return $attribute;
    }

    /**
     * @param array $params
     * @return Attribute
     * @throws Throwable
     */
    public function update(array $params = []): Attribute
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
    private function updateByParams($params): Attribute
    {
        $attribute = $this->attribute->findOrFail($params['id']);
        $attribute->update($params);
        return $attribute;
    }

    /**
     * @param array $params
     * @return AttributeValue
     * @throws Throwable
     */
    public function createWithAttrValue(array $params = []): AttributeValue
    {
        DB::beginTransaction();
        try {
            $attributeValue = $this->createWithAttrValueByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $attributeValue;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function createWithAttrValueByParams($params): AttributeValue
    {
        $attributeValue = $this->attributeValue->create($params);
        return $attributeValue;
    }

    /**
     * @param array $params
     * @return AttributeValue
     * @throws Throwable
     */
    public function updateWithAttrValue(array $params = []): AttributeValue
    {
        DB::beginTransaction();
        try {
            $attributeValue = $this->updateWithAttrValueByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $attributeValue;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateWithAttrValueByParams($params): AttributeValue
    {
        $attributeValue = $this->attributeValue->findOrFail($params['id']);
        $attributeValue->update($params);
        return $attributeValue;
    }
}

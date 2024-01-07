<?php

namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Models\Major_Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class MajorCategoryService
 * @package App\Services
 */
class MajorCategoryService
{

    public function __construct(Major_Category $majorCategory)
    {
        $this->majorCategory = $majorCategory;
    }

    /**
     * @param array $params
     * @return Major_Category
     * @throws Throwable
     */
    public function create(array $params = []): Major_Category
    {
        DB::beginTransaction();
        try {
            $major_category = $this->createByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $major_category;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function createByParams($params): Major_Category
    {
        $major_category = $this->majorCategory->create($params);
        return $major_category;
    }


    /**
     * @param array $params
     * @return Category
     * @throws Throwable
     */
    public function update(array $params = []): Major_Category
    {
        DB::beginTransaction();
        try {
            $major_category = $this->updateByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $major_category;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateByParams($params): Major_Category
    {
        $major_category = $this->majorCategory->findOrFail($params['id']);
        $major_category->update($params);
        return $major_category;
    }
}

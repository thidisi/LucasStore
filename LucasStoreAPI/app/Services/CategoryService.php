<?php

namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class CategoryService
 * @package App\Services
 */
class CategoryService
{

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @param array $params
     * @return Category
     * @throws Throwable
     */
    public function create(array $params = []): Category
    {
        DB::beginTransaction();
        try {
            $category = $this->createByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $category;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function createByParams($params): Category
    {
        $category = $this->category->create($params);
        return $category;
    }


    /**
     * @param array $params
     * @return Category
     * @throws Throwable
     */
    public function update(array $params = []): Category
    {
        DB::beginTransaction();
        try {
            $category = $this->updateByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $category;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateByParams($params): Category
    {
        $category = $this->category->findOrFail($params['id']);
        if ($params['checkAvatar']) {
            $images = $params['avatar'];
            if ($category->avatar != null) {
                if (Storage::disk('public')->exists($category->avatar)) {
                    Storage::disk('public')->delete($category->avatar);
                }
            }
            $path = Storage::disk('public')->put('avatarCategories', $images);
            $data['avatar'] = $path ? $path : $category->avatar;

        }
        $data['name'] = $params['name'];
        $data['status'] = $params['status'];
        $category->update($data);
        return $category;
    }
}

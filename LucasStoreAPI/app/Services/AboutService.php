<?php

namespace App\Services;

use App\Models\About;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class AboutService
 * @package App\Services
 */
class AboutService
{

    public function __construct(About $about)
    {
        $this->about = $about;
    }

    /**
     * @param array $params
     * @return About
     * @throws Throwable
     */
    public function create(array $params = []): About
    {
        DB::beginTransaction();
        try {
            $about = $this->createByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $about;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function createByParams($params): About
    {
        $about = $this->about->create($params);
        return $about;
    }


    /**
     * @param array $params
     * @return About
     * @throws Throwable
     */
    public function update(array $params = []): About
    {
        DB::beginTransaction();
        try {
            $about = $this->updateByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $about;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateByParams($params): About
    {
        $about = $this->about->findOrFail($params['id']);
        // if ($params['checkAvatar']) {
        //     $images = $params['avatar'];
        //     if ($category->avatar != null) {
        //         if (Storage::disk('public')->exists($category->avatar)) {
        //             Storage::disk('public')->delete($category->avatar);
        //         }
        //     }
        //     $path = Storage::disk('public')->put('avatarCategories', $images);
        //     $data['avatar'] = $path ? $path : $category->avatar;

        // }
        // $data['name'] = $params['name'];
        // $data['status'] = $params['status'];
        // $category->update($data);
        return $about;
    }
}

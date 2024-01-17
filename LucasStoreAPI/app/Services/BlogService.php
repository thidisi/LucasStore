<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class BlogService
{

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    /**
     * @param array $params
     * @return Blog
     * @throws Throwable
     */
    public function create(array $params = []): Blog
    {
        DB::beginTransaction();
        try {
            $blog = $this->createByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $blog;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function createByParams($params): Blog
    {
        $path = Storage::disk('public')->put('imageBlogs', $params['image']);
        $params['image'] = $path;
        $blog = $this->blog->create($params);
        return $blog;
    }


    /**
     * @param array $params
     * @return Blog
     * @throws Throwable
     */
    public function update(array $params = []): Blog
    {
        DB::beginTransaction();
        try {
            $blog = $this->updateByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $blog;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateByParams($params): Blog
    {
        $blog = $this->blog->findOrFail($params['id']);
        if (is_uploaded_file($params['image'])) {
            $images = $params['image'];
            if ($blog->image != null) {
                if (Storage::disk('public')->exists($blog->image)) {
                    Storage::disk('public')->delete($blog->image);
                }
            }
            $path = Storage::disk('public')->put('imageBlogs', $images);
            $params['image'] = $path ? $path : $blog->image;
        }else{
            $params['image'] = $blog->image;
        }
        $blog->update($params);
        return $blog;
    }
}

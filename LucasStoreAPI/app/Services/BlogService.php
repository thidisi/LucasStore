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
        dd($params);
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

        // if ($params['checkFile']) {
        //     $images = $params['banner'];

        //     if ($blog->banner != null) {
        //         if (Storage::disk('public')->exists($blog->banner)) {
        //             Storage::disk('public')->delete($blog->banner);
        //         }
        //     }
        //     $path = Storage::disk('public')->put('imageBlog', $images);
        //     $data['banner'] = $path ? $path : $blog->banner;
        // }
        // $data['title'] = $params['title'];
        // $data['highlight'] = $params['highlight'];
        // $data['content'] = $params['content'];
        // $blog->slug = null;
        // $blog->update($data);
        return $blog;
    }
}

<?php

namespace App\Services;

use App\Models\Slide;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class SlideService
 * @package App\Services
 */
class SlideService
{

    public function __construct(Slide $slide)
    {
        $this->slide = $slide;
    }

    /**
     * @param array $params
     * @return Slide
     * @throws Throwable
     */
    public function create(array $params = []): Slide
    {
        DB::beginTransaction();
        try {
            $slide = $this->createByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $slide;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function createByParams($params): Slide
    {
        $path = Storage::disk('public')->put('imageSlides', $params['image']);
        $params['image'] = $path;
        $slide = $this->slide->create($params);
        return $slide;
    }


    /**
     * @param array $params
     * @return Slide
     * @throws Throwable
     */
    public function update(array $params = []): Slide
    {
        DB::beginTransaction();
        try {
            $slide = $this->updateByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $slide;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateByParams($params): Slide
    {
        $slide = $this->slide->findOrFail($params['id']);
        if (is_uploaded_file($params['image'])) {
            $images = $params['image'];
            if ($slide->image != null) {
                if (Storage::disk('public')->exists($slide->image)) {
                    Storage::disk('public')->delete($slide->image);
                }
            }
            $path = Storage::disk('public')->put('imageSlides', $images);
            $params['image'] = $path ? $path : $slide->image;
        }else{
            $params['image'] = $slide->image;
        }
        $slide->update($params);
        return $slide;
    }
}

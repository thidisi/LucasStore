<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class ContactService
 * @package App\Services
 */
class ContactService
{

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @param array $params
     * @return Contact
     * @throws Throwable
     */
    public function create(array $params = []): Contact
    {
        DB::beginTransaction();
        try {
            $contact = $this->createByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $contact;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function createByParams($params): Contact
    {
        $contact = $this->contact->create($params);
        return $contact;
    }


    /**
     * @param array $params
     * @return Contact
     * @throws Throwable
     */
    public function update(array $params = []): Contact
    {
        DB::beginTransaction();
        try {
            $contact = $this->updateByParams($params);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $contact;
    }

    /**
     * @param $params
     * @return mixed
     */
    private function updateByParams($params): Contact
    {
        $contact = $this->contact->findOrFail($params['id']);
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
        return $contact;
    }
}

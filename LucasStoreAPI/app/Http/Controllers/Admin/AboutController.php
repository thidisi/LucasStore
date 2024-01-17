<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function __construct(About $about)
    {
        $this->about = $about;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // try {
        $about = $this->about->firstOrFail();
        return response()->json([
            'about' => $about,
        ], 200);
        // } catch (\Throwable $th) {
        //     return response()->json(['message' => config('const.message_error')], 403);
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function save(Request $request)
    {
        try {
            $about = $this->about->firstOrFail();
            $data = $request->all();
            $about->title = $data['title'] ? $data['title'] : null;
            $about->email = $data['email'] ? $data['email'] : null;
            $about->phone = $data['phone'] ? $data['phone'] : null;
            $about->phone_second =  $data['phone_second'] ? $data['phone_second'] : null;
            $about->address = $data['address'] ? $data['address'] : null;
            $about->address_second = $data['address_second'] ? $data['address_second'] : null;
            $about->branch = $data['branch'] ? $data['branch'] : null;
            $about->branch_second = $data['branch_second'] ? $data['branch_second'] : null;
            $about->link_address_fb = $data['link_address_fb'] ? $data['link_address_fb'] : null;
            $about->link_address_youtube = $data['link_address_youtube'] ? $data['link_address_youtube'] : null;
            $about->link_address_zalo = $data['link_address_zalo'] ? $data['link_address_zalo'] : null;
            $about->link_address_instagram = $data['link_address_instagram'] ? $data['link_address_instagram'] : null;

            if (isset($data['logo']) && is_uploaded_file($data['logo'])) {
                $images = $data['logo'];
                if ($about->logo != null) {
                    if (Storage::disk('public')->exists($about->logo)) {
                        Storage::disk('public')->delete($about->logo);
                    }
                }
                $path = Storage::disk('public')->put('logoShop', $images);
                $data['logo'] = $path;
            } else {
                $data['logo'] = $about->logo;
            }
            $about->update($data);
            return response()->json([
                'about' => $about,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

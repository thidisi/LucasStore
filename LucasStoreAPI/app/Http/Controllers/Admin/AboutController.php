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
        try {
            $about = $this->about->firstOrFail();
            return response()->json([
                'about' => $about,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $about = $this->about->findOrFail($id);
            $about->title = $request->get('title');
            $about->email = $request->get('email');
            $about->phone = $request->get('phone');
            $about->phone_second = $request->get('phone_second') ? $request->get('phone_second') : null;
            $about->address = $request->get('address');
            $about->address_second = $request->get('address_second') ? $request->get('address_second') : 'During the update';
            $about->branch = $request->get('branch');
            $about->branch_second = $request->get('branch_second') ? $request->get('branch_second') : 'During the update';
            $about->link_address_fb = $request->get('link_address_fb') ? $request->get('link_address_fb') : 'During the update';
            $about->link_address_youtube = $request->get('link_address_youtube') ? $request->get('link_address_youtube') : 'During the update';
            $about->link_address_zalo = $request->get('link_address_zalo') ? $request->get('link_address_zalo') : 'During the update';
            $about->link_address_instagram = $request->get('link_address_instagram') ? $request->get('link_address_instagram') : 'During the update';

            $nameAvatar = null;
            if ($request->hasFile('logo_new')) {
                if ($request->file('logo_new')->isValid()) {
                    $nameAvatar = Storage::disk('public')->put('logoShop', $request->file('logo_new'));
                    if (Storage::disk('public')->exists($about->logo)) {
                        Storage::disk('public')->delete($about->logo);
                    }
                }
            } else {
                $nameAvatar = $request->get('logo_old');
            }
            $about->logo = $nameAvatar;
            $about->update();
            return response()->json([
                'about' => $about,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => config('const.message_error')], 403);
        }
    }
}

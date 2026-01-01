<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\DeleteTemporaryImgRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\TemporarySaveImgRequest;
use App\Models\BusinessModel;
use App\Models\TemporaryProductImageModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Storage;

class ProductController extends Controller
{
    public function create()
    {
        return Inertia::render('product/Create');
    }

    public function temporarySaveImg(TemporarySaveImgRequest $request)
    {
        $img = $request->validated()['img'];
        $business = BusinessModel::where('user_id', auth()->id())->first();
        if($business == null){
            return response()->json(['error_business' => __('business.no_business')]);
        }
        $path = Storage::disk('public')->put('/images', $img);
        $temp_img = TemporaryProductImageModel::create(['business_id'=> $business->id, 'temp_img' => $path]);
        return response()->json(['img_id' => $temp_img->id, 'path' => url('build/storage/' . $path)]);
    }

    public function deleteTemporaryImg(DeleteTemporaryImgRequest $request)
    {
        $img_id = $request->validated()['img_id'];
        $img = TemporaryProductImageModel::where('id', $img_id)->first();
        Storage::disk('public')->delete($img->temp_img);
        $img->delete();
        return response()->json(['success' => true]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        return $data;
    }
}

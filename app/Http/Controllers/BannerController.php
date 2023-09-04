<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerCollection;
use App\Services\BannerService;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BannerService $bannerService): BannerCollection
    {
        return new BannerCollection($bannerService->list());
    }
}

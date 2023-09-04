<?php

namespace App\Services;

use App\Repositories\BannerRepository;

/**
 * 商業邏輯層
 */
class BannerService
{
    protected BannerRepository $bannerRepository;

    /**
     * 依賴注入
     */
    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * 列表
     */
    public function list()
    {
        return $this->bannerRepository->list();
    }
}

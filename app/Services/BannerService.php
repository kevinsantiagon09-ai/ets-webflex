<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Support\Str;

class BannerService
{
    public function create(array $data): Banner
    {
        $data['uuid'] = Str::uuid();
        $data['user_id'] = auth()->id();

        return Banner::create($data);
    }

    public function update(Banner $banner, array $data): Banner
    {
        $banner->update($data);

        return $banner;
    }

    public function delete(Banner $banner): void
    {
        $banner->delete();
    }
}
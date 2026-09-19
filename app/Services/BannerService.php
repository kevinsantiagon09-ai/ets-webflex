<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class BannerService
{
    /**
     * @param  array{estado_id: int|string, image: UploadedFile, titulo: string}  $data
     */
    public function create(array $data, User $user): Banner
    {
        $data['uuid'] = Str::uuid();
        $data['user_id'] = $user->id;

        if (isset($data['image'])) {
            $data['image_path'] = $data['image']
                ->store('banners', 'public');

            unset($data['image']);
        }

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

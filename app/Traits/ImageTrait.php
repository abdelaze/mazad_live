<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use App\Models\Image as ImageModel;

trait ImageTrait
{
    public function storeImages($images, string $file, object $modelObject, int $width = 250, int $height = 250): void
    {
        $this->checkFolderExist($file);
        if(is_array($images)){
            foreach ($images as $image) {
                $hashImageName = $image->hashName();
                $reSize = Image::make($image)->resize($width, $height);
                $reSize->save(public_path("storage/uploads/$file/$hashImageName"));
                $this->createImage($modelObject, $hashImageName);
            }
        }else{
            $hashImageName = $images->hashName();
            $reSize = Image::make($images)->resize($width, $height);
            $reSize->save(public_path("storage/uploads/$file/$hashImageName"));
            $this->createImage($modelObject, $hashImageName);
        }
    }

    public function updateImages($oldImage ,$images, string $file, object $modelObject, int $width = 250, int $height = 250): void
    {
        $this->storeImages($images, $file, $modelObject, $width, $height);
        $this->deleteImages($oldImage, $file);
    }

    public function deleteImages($images,string $file): void
    {
        if (file_exists(public_path("storage/uploads/$file/$images->name"))) {
            unlink(public_path("storage/uploads/$file/$images->name"));
        }
        //$images->delete();
    }

    private function createImage(object $modelObject, string $hashImageName): object
    {
        return ImageModel::create(['imageable_type' => get_class($modelObject), 'imageable_id' => $modelObject->id, 'name' => $hashImageName]);
    }

    private function checkFolderExist(string $file): void
    {
        if (!file_exists(public_path("storage/uploads/$file"))) {
            mkdir(public_path("storage/uploads/$file"), 0777, true);
        }
    }
}

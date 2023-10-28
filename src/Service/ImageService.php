<?php

namespace App\Service;

class ImageService
{
    public function updateImages($entityInstance, $getImagesFunctionName): array
    {
        $previousImages = [...call_user_func([$entityInstance, $getImagesFunctionName])->getSnapshot()];
        $currentImages = [...call_user_func([$entityInstance, $getImagesFunctionName])];

        $tab1 = array_map(fn ($image) => $image->getFilename(), $previousImages);
        $tab2 = array_map(fn ($image) => $image->getFilename(), $currentImages);
        return array_diff($tab1, $tab2);
    }
}
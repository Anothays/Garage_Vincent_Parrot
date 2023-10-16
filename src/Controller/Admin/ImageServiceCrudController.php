<?php

namespace App\Controller\Admin;

use App\Entity\ImageService;

class ImageServiceCrudController extends ImageCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImageService::class;
    }

}

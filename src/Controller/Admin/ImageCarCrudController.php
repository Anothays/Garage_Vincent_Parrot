<?php

namespace App\Controller\Admin;

use App\Entity\ImageCar;

class ImageCarCrudController extends ImageCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImageCar::class;
    }

}

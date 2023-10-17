<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Validator\Constraints\EasyAdminFile;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function verifyAltWhenFilenameIsEmpty($filename, ExecutionContextInterface $context): void
    {
        $file = $context->getObject()->getParent()->getNormData();
        $entity = $context->getRoot()->getData();
        if (method_exists($entity,'getImageCars')) {
            $image = [...$context->getRoot()->getData()->getImageCars()][0];
        } else {
            $image = $context->getRoot()->getData()->getImageServices()[0];
        }
        if (empty($file) && !empty($image->getAlt())) {
            $context
                ->buildViolation('Vous devez sélectionner une image si vous renseignez le texte alternatif')
                ->addViolation();
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('filename', 'Image')
                ->setUploadDir('public/media/uploads')
                ->setUploadedFileNamePattern('[name]-[[year]_[month]_[day]]-[timestamp].[extension]')
                ->setFormTypeOption('constraints', [
                    new EasyAdminFile([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le fichier doit être au format jpeg ou png.',
                    ]),
                    new Callback([$this, 'verifyAltWhenFilenameIsEmpty']),
                ])
            ,
            TextField::new('alt', 'texte alternatif')
                ->setHelp('Décrivez votre image en une phrase simple et courte'),
        ];
    }
}

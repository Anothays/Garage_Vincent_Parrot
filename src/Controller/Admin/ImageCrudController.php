<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Validator\Constraints\EasyAdminFile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return Image::class;}
    public function verifyAltWhenFilenameIsEmpty($filename, ExecutionContextInterface $context, $payload): void
    {
        /**
         * Get uploaded file and entity
         */
        $file = $context->getObject()->getParent()->getNormData();
        $entity = $context->getRoot()->getData();
        $image = null;
        if (method_exists($entity,'getImageCars')) {

            /**
             * Images Cars case
             */
            $image = [...$context->getRoot()->getData()->getImageCars()][0];

        } else if (method_exists($entity,'getImageServices')) {

            /**
             * Images Services case
             */
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
            yield ImageField::new('filename', 'Image')
                ->setUploadDir('public/media/uploads')
                ->setBasePath('/media/uploads')
                ->setUploadedFileNamePattern('[name]-[[year]_[month]_[day]]-[timestamp].[extension]')
                ->setFormType(FileUploadType::class)
                ->setFormTypeOptions([
                    'constraints' => [
                        new EasyAdminFile([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Le fichier doit être au format jpeg ou png.',
                            'maxSize' => 2000000, // 2 Mb
                            'maxSizeMessage' => 'Le fichier ne peut pas excéder 2 Mb',
                            'uploadIniSizeErrorMessage' => 'uploadIniSizeErrorMessage',
                            'uploadFormSizeErrorMessage' => 'uploadFormSizeErrorMessage',
                            'uploadErrorMessage' => 'uploadErrorMessage'
                        ]),
                        new Callback([$this,'verifyAltWhenFilenameIsEmpty']),
                    ],
                    'allow_delete' => false,
                ])
            ;
            yield TextField::new('alt', 'texte alternatif')
                ->setHelp('Décrivez votre image en une phrase simple et courte')
            ;
    }


}

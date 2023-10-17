<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\Model\FileUploadState;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use function Symfony\Component\String\u;


class CarsCrudController extends AbstractCrudController
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ){}

    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Véhicule')
            ->setEntityLabelInPlural('Véhicules')
            ->setPageTitle('index', 'Liste des véhicules')
            ->setPaginatorPageSize(10)
            ->showEntityActionsInlined()
            ->setDateIntervalFormat('%%y Year(s) %%m Month(s) %%d Day(s)')
            ;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets);
    }
    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('carConstructor', 'Marque');
            yield TextField::new('carModel', 'Modèle');
            yield TextField::new('licensePlate', 'Plaque')->formatValue(function ($value){return strtoupper($value);});
            yield TextField::new('carEngine', 'Moteur');
            yield IntegerField::new('mileage', 'Kilométrage');
            yield NumberField::new('price', 'Prix');
            yield DateField::new('registrationDate', 'Date de mise en circulation');
            yield AssociationField::new('contactMessages', "Demandes")->onlyOnIndex()->setSortable(false);
            yield AssociationField::new('garage', 'Lieu de stockage');
            yield ImageField::new('imageCars[0].filename', 'image')
                ->setBasePath("/media/uploads")
//                ->setTemplatePath('admin/custom_imageField.html.twig')
                ->onlyOnIndex()
            ;
            yield BooleanField::new('published', 'Annonce visible');

            yield DateTimeField::new('createdAt', 'Crée le')
                ->hideOnIndex()
                ->hideWhenCreating()
                ->setFormTypeOptions([
                'label' => 'Crée le',
                'disabled' => 'disabled'
                ])
            ;
            yield DateTimeField::new('modifiedAt', 'Modifié le')
                ->hideOnIndex()
                ->hideWhenCreating()
                ->setFormTypeOptions([
                    'label' => 'Dernière modification',
                    'disabled' => 'disabled'
                ])
            ;
            yield CollectionField::new('imageCars','photos du véhicule')
                ->useEntryCrudForm(ImageCarCrudController::class)
//                    ->setEntryType(ImageType::class)
                ->renderExpanded()
                ->onlyOnForms()
            ;

    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
//        $this->createMediaFolder($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
//        $this->removeMediaFolder($entityInstance);
        $images = [...$entityInstance->getImageCars()];
        foreach ($images as $image) {
            if (is_file("media/uploads/".$image->getFilename())) {
                unlink("media/uploads/".$image->getFilename());
            }
        }
        parent::deleteEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /**
         * Suppression des images si la mise à jour de l'entité l'impose
         */
        $previousImages = [...$entityInstance->getImageCars()->getSnapshot()];
        $currentImages = [...$entityInstance->getImageCars()];
        foreach (array_diff($previousImages, $currentImages) as $image) {
            if (is_file("media/uploads/".$image->getFilename())) {
                unlink("media/uploads/".$image->getFilename());
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

//    public function createMediaFolder(Car $entityInstance): void
//    {
//        $licencePlate = strtoupper($entityInstance->getLicensePlate());
//        mkdir("media/{$licencePlate}");
//    }

//    public function removeMediaFolder(Car $entityInstance): void
//    {
//        $licencePlate = strtoupper($entityInstance->getLicensePlate());
//        rmdir("media/{$licencePlate}");
//    }
    protected function processUploadedFiles(FormInterface $form): void
    {
        /** @var FormInterface $child */
        foreach ($form as $child) {
            $config = $child->getConfig();

            if (!$config->getType()->getInnerType() instanceof FileUploadType) {
                if ($config->getCompound()) {
                    $this->processUploadedFiles($child);
                }

                continue;
            }

            /** @var FileUploadState $state */
            $state = $config->getAttribute('state');


            if (!$state->isModified()) {
                continue;
            }
            $uploadDelete = $config->getOption('upload_delete');
            if ($state->hasCurrentFiles() && ($state->isDelete() || (!$state->isAddAllowed() && $state->hasUploadedFiles()))) {
                foreach ($state->getCurrentFiles() as $file) {
                    $uploadDelete($file);
                }
                $state->setCurrentFiles([]);
            }
            $filePaths = (array) $child->getData();

//            /** @var Car $currentInstance */
//            $currentInstance = $form->getRoot()->getData();

//            $uploadDir = $config->getOption('upload_dir') . strtoupper($currentInstance->getLicensePlate());
            $uploadDir = $config->getOption('upload_dir') ;


//            if (!is_dir($uploadDir)) {
//                $this->createMediaFolder($currentInstance);
//            }

            $uploadNew = $config->getOption('upload_new');

            foreach ($state->getUploadedFiles() as $index => $file) {
                $fileName = u($filePaths[$index])->replace($uploadDir, '')->toString();
                $uploadNew($file, $uploadDir, $fileName);
            }
        }
    }
}

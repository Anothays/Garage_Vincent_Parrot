<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
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

    public function __construct( private ImageService $imageService){}

    public static function getEntityFqcn(): string{ return Car::class;}
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
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->update(Crud::PAGE_INDEX, Action::NEW, fn ($action) => $action->setLabel('Ajouter un véhicule'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, fn ($action) => $action->setLabel('Valider et ajouter un nouveau véhicule'))
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn ($action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, fn ($action) => $action->setLabel('Valider et continuer'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ;
    }
    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Caractéristiques');
            yield TextField::new('carConstructor', 'Marque');
            yield TextField::new('carModel', 'Modèle');
            yield TextField::new('licensePlate', 'Plaque')
                ->formatValue(function ($value){return strtoupper($value);})
            ;
            yield TextField::new('carEngine', 'Moteur');
            yield IntegerField::new('mileage', 'Kilométrage');
            yield NumberField::new('price', 'Prix');
            yield DateField::new('registrationDate', 'Date de mise en circulation');
            yield AssociationField::new('contactMessages', "Demandes")
                ->onlyOnIndex()
                ->setSortable(false)
            ;
            yield AssociationField::new('garage', 'Lieu de stockage');
            yield ImageField::new('imageCars[0].filename', 'image')
                ->setBasePath("/media/uploads")
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
        yield FormField::addTab('Photos');
        yield CollectionField::new('imageCars','photos du véhicule')
            ->useEntryCrudForm(ImageCarCrudController::class)
            ->renderExpanded()
            ->onlyOnForms()
            ->addJsFiles('js/easyAdminCollectionField.js')
        ;
    }
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
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
        foreach ($this->imageService->updateImages($entityInstance, 'getImageCars') as $image) {
            if (is_file("media/uploads/".$image)) {
                unlink("media/uploads/".$image);
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }
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

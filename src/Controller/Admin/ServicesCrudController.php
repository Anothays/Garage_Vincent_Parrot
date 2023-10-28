<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class ServicesCrudController extends AbstractCrudController
{
    public function __construct( private ImageService $imageService) {}

    public static function getEntityFqcn(): string { return Service::class;}

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Caractéristiques');
        yield TextField::new('name')->setLabel('Nom de la prestation');
        yield NumberField::new('price')->setLabel('Prix');
        yield TextareaField::new('description')->setLabel('Description de la prestation');
        yield BooleanField::new('published');
        yield FormField::addTab('Photos');
        yield CollectionField::new('imageServices', 'Image')
            ->useEntryCrudForm(ImageServiceCrudController::class)
            ->renderExpanded()
            ->onlyOnForms()
            ->addJsFiles('js/easyAdminCollectionField.js')
        ;
        yield ImageField::new('imageServices[0].filename', 'image')
            ->setBasePath("media/uploads")
            ->onlyOnIndex()
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Prestation')
            ->setEntityLabelInPlural('Prestations')
            ->setPageTitle('index', 'Liste des prestations')
            ->setPaginatorPageSize(10)
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->update(Crud::PAGE_INDEX, Action::NEW, fn ($action) => $action->setLabel('Créer une nouvelle prestation'))
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn ($action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, fn ($action) => $action->setLabel('Valider et continuer'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, fn ($action) => $action->setLabel('Valider et créer un nouveau service'))
            ;
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /**
         * Suppression des images si la mise à jour de l'entité l'impose
         */
        foreach ($this->imageService->updateImages($entityInstance, 'getImageServices') as $filename) {
            if (is_file("media/uploads/" . $filename)) {
                unlink("media/uploads/" . $filename);
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $images = [...$entityInstance->getImageServices()];
        foreach ($images as $image) {
            if (is_file("media/uploads/".$image->getFilename())) {
                unlink("media/uploads/".$image->getFilename());
            }
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }

}

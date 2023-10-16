<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name')->setLabel('Nom de la prestation');
        yield NumberField::new('price')->setLabel('Prix');
        yield TextareaField::new('description')->setLabel('Description de la prestation');
        yield AssociationField::new('imageService', 'Image')
            ->renderAsEmbeddedForm()
            ->onlyOnForms()
        ;
        yield ImageField::new('imageService.filename')
            ->setBasePath("media/uploads")
            ->onlyOnIndex()
        ;
        yield BooleanField::new('published');
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
        return parent::configureActions($actions);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $previousImages = [...$entityInstance->getImageService()->getSnapshot()];
        $currentImages = [...$entityInstance->getImageService()];
        foreach (array_diff($previousImages, $currentImages) as $image) {
            if (is_file("media/uploads/" . $image->getFilename())) {
                unlink("media/uploads/" . $image->getFilename());
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $image = $entityInstance->getImageService();
        if (is_file("media/uploads/".$image->getFilename())) {
            unlink("media/uploads/".$image->getFilename());
        }
        parent::deleteEntity($entityManager, $entityInstance);
    }

}

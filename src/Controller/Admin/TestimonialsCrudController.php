<?php

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use App\Entity\TestimonialApproved;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use JetBrains\PhpStorm\NoReturn;

class TestimonialsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Témoignage')
            ->setEntityLabelInPlural('Témoignages')
            ->setPageTitle('index', 'Liste des témoignages')
            ->setPaginatorPageSize(20)
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_DETAIL, function($testimonial){ return 'Avis de ' . $testimonial->getAuthor();})
        ;
    }
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('author')->setLabel('Pseudo');
        yield ChoiceField::new('note')->setLabel('note')
            ->setChoices([
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
            ])
        ;
        yield TextField::new('comment')->setLabel('Commentaire');
        yield DateTimeField::new('created_at', 'Crée le')
            ->hideWhenCreating()
            ->setFormat('dd/MM/yyyy à HH:mm')
            ->setFormTypeOptions([
                'label' => 'Crée le',
                'disabled' => 'disabled'
            ])
        ;
        yield DateTimeField::new('modified_at', 'Modifié le')
            ->hideWhenCreating()
            ->setFormat('dd/MM/yyyy à HH:mm')
            ->setTimezone("Europe/Paris")
            ->setFormTypeOptions([
                'label' => 'Dernière modification',
                'disabled' => 'disabled'
            ])
        ;
        yield BooleanField::new('approved', 'Visible sur le site')
            ->hideOnDetail();
        yield AssociationField::new('approval', 'Approuvé par')
            ->onlyOnDetail()
        ;
        yield TextField::new('createdBy', 'créé par');
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    #[NoReturn] public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->getUser();
        $testimonial = $entityInstance;
        $testimonial->setCreatedBy($user->getFullname());
        if ($testimonial->isApproved()) {
            $approval = new TestimonialApproved();
            $approval->setApprovedBy($user);
            $testimonial->setApproval($approval);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }

    #[NoReturn] public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->getUser();

        /**
         * @var Testimonial $entityInstance
         */
        if ($entityInstance->isApproved()) {
            if ($entityInstance->getApproval() === null) {
                $testimonialApproved = new TestimonialApproved();
                $testimonialApproved->setApprovedBy($user);
                $entityInstance->setApproval($testimonialApproved);
            }
        } else {
            if ($entityInstance->getApproval() !== null)   {
                $testimonialApproved = $entityInstance->getApproval();
                $entityManager->remove($testimonialApproved);
                $entityInstance->setApproval(null);
            }
        }
        parent::updateEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }

}

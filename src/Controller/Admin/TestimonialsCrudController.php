<?php

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use App\Entity\TestimonialApproved;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use JetBrains\PhpStorm\NoReturn;

class TestimonialsCrudController extends AbstractCrudController
{
    public function __construct(private EntityManagerInterface $entityManager){}

    public static function getEntityFqcn(): string { return Testimonial::class;}
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Témoignage')
            ->setEntityLabelInPlural('Témoignages')
            ->setPageTitle('index', 'Liste des témoignages')
            ->setPaginatorPageSize(20)
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_DETAIL, function($testimonial){ return 'Avis de ' . $testimonial->getAuthor();})
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('author')->setLabel('Pseudo')
            ->formatValue(function ($value, $message) {
                return !$message->isIsReadByStaff() ? "⚠️ {$value}" : $value;
            });
        ;
        yield ChoiceField::new('note')->setLabel('note')->setChoices(['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5,]);
        yield TextField::new('comment')->setLabel('Commentaire')->setSortable(false);
        yield DateTimeField::new('modifiedAt', 'Modifié le')
            ->hideWhenCreating()
            ->hideOnIndex()
            ->setFormat('dd/MM/yyyy à HH:mm')
            ->setTimezone("Europe/Paris")
            ->setFormTypeOptions([
                'label' => 'Dernière modification',
                'disabled' => 'disabled'
            ]);
        yield AssociationField::new('approval', 'Approuvé par')->onlyOnDetail();
        yield TextField::new('createdBy', 'créé par')->hideOnForm();
        yield BooleanField::new('approved', 'Visible sur le site')->hideOnDetail();
        yield DateTimeField::new('createdAt', 'Crée le')
            ->hideWhenCreating()
            ->setSortable(true)
            ->setFormat('dd/MM/yyyy à HH:mm')
            ->setFormTypeOptions([
                'label' => 'Crée le',
                'disabled' => 'disabled'
            ])
        ;
    }
    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, fn ($action) => $action->setLabel('Créer un avis'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, fn ($action) => $action->setLabel('Valider et créer un nouvel avis'))
            ->update(Crud::PAGE_INDEX, Action::DETAIL, fn ($action) => $action->setLabel('Voir'))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_DETAIL, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_DETAIL, Action::INDEX, fn ($action) => $action->setLabel('Retour'))
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
    public function detail(AdminContext $context)
    {
        /** @var Testimonial $testimonial */
        $testimonial = $context->getEntity()->getInstance();
        if (!$testimonial->isIsReadByStaff()) {
            $testimonial->setIsReadByStaff(true);
            $this->entityManager->persist($testimonial);
            $this->entityManager->flush();
        }
        return parent::detail($context); // TODO: Change the autogenerated stub
    }


}

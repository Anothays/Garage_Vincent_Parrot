<?php

namespace App\Controller\Admin;

use App\Entity\UserStaffMember;
use App\Form\CustomPasswordType;
use App\Repository\UserRepository;
use App\Repository\UserStaffMemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UsersStaffMemberCrudController extends AbstractCrudController
{

    public function __construct(private RequestStack $requestStack, private UserPasswordHasherInterface $userPasswordHasher){}

    public static function getEntityFqcn(): string
    {
        return UserStaffMember::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Employé')
            ->setEntityLabelInPlural('Employés')
            ->setPageTitle('index','Liste des employés')
            ->showEntityActionsInlined()
            ->setFormOptions(
              ['validation_groups' => ['registration']],
              ['validation_groups' => ['update']],
            )
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermissions([
                Action::DELETE => 'ROLE_SUPER_ADMIN',
                Action::EDIT => 'ROLE_SUPER_ADMIN',
                Action::NEW => 'ROLE_SUPER_ADMIN',
            ])
            ->add( Crud::PAGE_INDEX,Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, fn ($action) => $action->setLabel('Ajouter un nouveau membre'))
            ->update(Crud::PAGE_INDEX, Action::DETAIL, fn ($action) => $action->setLabel('Voir'))
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn ($action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_DETAIL, Action::DELETE, fn ($action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_DETAIL, Action::INDEX, fn ($action) => $action->setLabel('Retour'))
            ->update(Crud::PAGE_DETAIL, Action::EDIT, fn ($action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, fn ($action) => $action->setLabel('Valider et continuer'))
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, fn ($action) => $action->setLabel('Valider'))
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('fullname', 'Nom complet')->onlyOnIndex(),
            TextField::new('firstname', 'Nom')->onlyOnForms(),
            TextField::new('lastname', 'Prénom')->onlyOnForms(),
            AssociationField::new('garage', 'Établissement')->setRequired(false),
            EmailField::new('email')
                ->onlyWhenCreating()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => EmailType::class,
                    'first_options' => ['label' => 'Email'],
                    'second_options' => ['label' => 'Confirmez l\'adresse email'],
                ]),
            EmailField::new('email', 'Email')
                ->hideWhenCreating()
                ->setDisabled()
        ];

        $passwordFieldCreate = TextField::new('password', 'Mot de passe')
            ->onlyWhenCreating()
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
            ])
        ;
        $fields[] = $passwordFieldCreate;


        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setRoles(["ROLE_ADMIN"]);

        /** @var UserStaffMember $entityInstance */
        $entityInstance->setPassword($this->userPasswordHasher->hashPassword($entityInstance, $entityInstance->getPassword()));
        parent::persistEntity($entityManager, $entityInstance);

    }

    public function changePassword(Request $request, UserStaffMemberRepository $userRepository): Response
    {
        $form = $this->createForm(CustomPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->getUser();
            $pw = $this->userPasswordHasher->hashPassword($currentUser, $form->getData()['password']);
            $userRepository->upgradePassword($currentUser, $pw);
            $this->addFlash('success','Votre mot de passe a bien été changé');

            return $this->redirectToRoute('admin');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger','Erreur de validation du mot de passe');

        }

        return $this->render('admin/reset-password.html.twig', [
            'form' => $form
        ]);

    }


}

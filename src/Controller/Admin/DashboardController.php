<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Entity\ContactMessage;
use App\Entity\Garage;
use App\Entity\Service;
use App\Entity\Schedule;
use App\Entity\Testimonial;
use App\Entity\UserCustomer;
use App\Entity\UserStaffMember;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct( private EntityManagerInterface $entityManager ){
//        phpinfo();
    }

    #[Route('/', name: 'admin')]
    public function index(): Response
    {
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(CarsCrudController::class)->generateUrl());
    }

    #[Route('/change-password', name: 'resetPassword')]
    public function changePassword() : Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UsersStaffMemberCrudController::class)->setAction('changePassword')->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Espace Administrateur')
            ->setFaviconPath("/media/logo4.webp")
            ->renderContentMaximized()
            ->disableDarkMode(true)
        ;
    }

    public function configureMenuItems(): iterable
    {
        $numberTestimonials = $this->entityManager->createQuery("select count(t) from App\Entity\Testimonial t where t.isReadByStaff = false")->getSingleScalarResult();
        $menuItemTestimonial = MenuItem::linkToCrud('Avis', 'fa-regular fa-comment-dots', Testimonial::class);
        $numberTestimonials > 0 ? $menuItemTestimonial->setBadge($numberTestimonials) : null;

        $numberContactMessage = $this->entityManager->createQuery("select count(c) from App\Entity\ContactMessage c where c.isReadByStaff = false")->getSingleScalarResult();
        $menuItemContactMessage = MenuItem::linkToCrud('Messages', 'fa-solid fa-envelope', ContactMessage::class);
        $numberContactMessage > 0 ? $menuItemContactMessage->setBadge($numberContactMessage) : null;

        yield MenuItem::linkToRoute('Retour vers le site', 'fa-solid fa-house', 'app_home_index');
        yield MenuItem::linkToCrud('Véhicules à vendre', 'fa-solid fa-car', Car::class);
        yield $menuItemContactMessage;
        yield $menuItemTestimonial;
        yield MenuItem::linkToCrud('Staff', 'fa-solid fa-users', UserStaffMember::class)->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Clients', 'fa-solid fa-users', UserCustomer::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Services', 'fa-solid fa-wrench', Service::class)->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Établissements', 'fa-solid fa-warehouse', Garage::class)->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Horaires', 'fa-solid fa-circle-info', Schedule::class)
            ->setPermission('ROLE_SUPER_ADMIN')
            ->setAction(Action::EDIT)
            ->setEntityId(1)
        ;

    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->displayUserAvatar(false)
            ->addMenuItems([MenuItem::linkToRoute('Changer de mot de passe', null, 'resetPassword')])
        ;
    }



}

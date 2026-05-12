<?php

namespace App\Controller\Admin;

use App\Repository\FilmRepository;
use App\Repository\ProjectionRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Controller\Admin\FilmCrudController;
use App\Controller\Admin\ProjectionCrudController;
use App\Controller\Admin\ReservationCrudController;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SubMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private FilmRepository $filmRepository,
        private ProjectionRepository $projectionRepository,
        private ReservationRepository $reservationRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'filmsCount' => $this->filmRepository->count([]),
            'projectionsCount' => $this->projectionRepository->count([]),
            'reservationsCount' => $this->reservationRepository->count([]),
            'usersCount' => $this->userRepository->count([]),
        ]);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion Cinema');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Statistics');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Management');
        yield MenuItem::subMenu('Cinema', 'fa fa-film')
            ->setSubItems([
                MenuItem::linkTo(FilmCrudController::class, 'Films', 'fa fa-film'),
                MenuItem::linkTo(ProjectionCrudController::class, 'Projections', 'fa fa-clapperboard'),
                MenuItem::linkTo(ReservationCrudController::class, 'Reservations', 'fa fa-ticket'),
            ]);
        yield MenuItem::subMenu('Users', 'fa fa-users')
            ->setSubItems([
                MenuItem::linkTo(UserCrudController::class, 'Users', 'fa fa-users'),
            ]);
    }
}

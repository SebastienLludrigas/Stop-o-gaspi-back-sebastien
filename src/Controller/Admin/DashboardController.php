<?php

namespace App\Controller\Admin;

use App\Entity\ProductPerso;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\CrudControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end users
            ->setTitle('<img src="image/logo_projet.png">')
            ->setFaviconPath('<img src="image/favicon.png');
    }

    public function configureMenuItems(): iterable
    {
        return
        [
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Produits', 'fa fa-edit', ProductPerso::class),
            MenuItem::linkToLogout('Déconnexion', 'fa fa-exit'),
        ];
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin.css');
    }
}
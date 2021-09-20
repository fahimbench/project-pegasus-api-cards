<?php

namespace App\Controller\Admin;

use App\Entity\Attribute;
use App\Entity\Card;
use App\Entity\CardType;
use App\Entity\Country;
use App\Entity\Icone;
use App\Entity\MonsterType;
use App\Entity\Rarity;
use App\Entity\Set;
use App\Entity\SetType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $cards = $this->getDoctrine()->getRepository(Card::class);
        $cardsnbr = $cards->count([]);
        $sets = $this->getDoctrine()->getRepository(Set::class);
        $setsnbr = $sets->count([]);
        return $this->render('Admin/index.html.twig', [
            'cardsnbr' => $cardsnbr,
            'setsnbr' => $setsnbr
        ]);
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Project Pegasus')
            ->setTitle('<img style="max-width: 50px" src="assets/images/oeil-mille2.png"> <b>Project Pegasus</b>')
            ->setFaviconPath('assets/images/oeil-mille2.png')
            ->renderContentMaximized()
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Les sets');
        yield MenuItem::linkToCrud('Tous les Sets', '', Set::class)->setController(SetCrudController::class);
        yield MenuItem::linkToCrud('Tous les Non-Vérifiés', '', Set::class)->setController(SetUnverifiedCrudController::class);

        yield MenuItem::section('Les cartes');
        yield MenuItem::linkToCrud('Toutes les cartes', '', Card::class)->setController(CardCrudController::class);
        yield MenuItem::linkToCrud('Toutes les cartes Monstre', '', Card::class)->setController(MonsterCrudController::class);
        yield MenuItem::linkToCrud('Toutes les cartes Magie', '', Card::class)->setController(MagicCrudController::class);
        yield MenuItem::linkToCrud('Toutes les cartes Piège', '', Card::class)->setController(TrapCrudController::class);
        yield MenuItem::linkToCrud('Toutes les Non-Vérifiés', '', Card::class)->setController(CardUnverifiedCrudController::class);


        yield MenuItem::section('Autres');
        yield MenuItem::linkToCrud('Attributes', '', Attribute::class);
        yield MenuItem::linkToCrud('Types des Cartes', '', CardType::class);
        yield MenuItem::linkToCrud('Types des Monstres', '', MonsterType::class);
        yield MenuItem::linkToCrud('Types des Sets', '', SetType::class);
        yield MenuItem::linkToCrud('Types des Raretés', '', Rarity::class);
        yield MenuItem::linkToCrud('Types des Pièges/Magie', '', Icone::class);
        yield MenuItem::linkToCrud('Pays', '', Country::class);
    }
}

<?php

namespace App\Controller;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Entity\Card;
use App\Repository\CardRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SetUniqueOperation extends AbstractController
{
    public function __construct()
    {

    }


    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke($id, SetRepository $setRepository)
    {
        return $setRepository->getSetCardsFilter($id);
    }
}
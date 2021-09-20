<?php

namespace App\Controller;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Repository\CardRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SetOperation extends AbstractController
{
    public function __construct()
    {

    }

    public function __invoke(Request $request, SetRepository $setRepository)
    {
        return $this->pagination($setRepository->getSetFilter($request));
    }

    protected function pagination(Paginator $data){
        $json = [
            "count" => $data->count(),
            "currentPage" => $data->getCurrentPage(),
            "itemsPerPage" => $data->getItemsPerPage(),
            "lastPage" => $data->getLastPage(),
            "totalItems" => $data->getTotalItems(),
            "data" => $data
        ];
        return $json;
    }
}
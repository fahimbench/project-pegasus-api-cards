<?php

namespace App\Controller;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardOperation extends AbstractController
{
    public function __construct()
    {

    }

    /**
     * @Route("/image/{id<\d+>}", name="card_image")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function image64($id, CardRepository $cardRepository)
    {
        $i64 = $cardRepository->getUrlToImage64($id);

        $response = new Response();

        if(is_null($i64) or empty($i64)){
            $file = 'assets/images/back-yugioh-card.jpg';
            $response->headers->set('Content-type', 'image/jpg' );
            $response->sendHeaders();
            $response->setContent(file_get_contents($file));
        }else{
            $i64 = base64_decode(explode(",", $i64)[1]);
            $response->headers->set('Content-type', 'image/jpg' );
            $response->headers->set('Content-length',  strlen($i64));
            $response->sendHeaders();
            $response->setContent( $i64 );
        }

        return $response;
    }

    public function __invoke(Request $request, CardRepository $cardRepository)
    {
        return $this->pagination($cardRepository->getCardFilter($request));
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
<?php

namespace App\Repository;

use App\Entity\Set;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Set|null find($id, $lockMode = null, $lockVersion = null)
 * @method Set|null findOneBy(array $criteria, array $orderBy = null)
 * @method Set[]    findAll()
 * @method Set[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SetRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 30;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Set::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSetCardsFilter($id){
        $qb = $this->createQueryBuilder('s');
        $qb = $qb
            ->andWhere("s.id = :ida")
            ->andWhere("s.isValid = 1")
            ->setParameter("ida", $id)
            ->getQuery()
            ->getOneOrNullResult();

        $tt = $qb->getCards()->filter(function($a) {
            return $a->getIsValid();
        });
//        dd($tt);
        $qb = $qb->setCards($tt);

//        dd(count($qb));
        if($qb === null){
            throw new NotFoundHttpException('not found');
        }

        return $qb;
    }

    public function getSetFilter(Request $request):Paginator
    {
        $page = (int) $request->query->get('page', 1);
        $name = (string) $request->query->get('name', null);
        $abr = (string) $request->query->get('abbreviated_name', null);

        $firstResult = ($page - 1) * self::ITEMS_PER_PAGE;

        $qb = $this->createQueryBuilder('s');

        if(!empty($name)){
            $qb
                ->setParameter('name', '%'.$name.'%')
                ->andWhere("s.name LIKE :name");
        }
        if(!empty($abr)){
            $qb
                ->setParameter('abr', '%'.$abr.'%')
                ->andWhere("s.abbreviatedName LIKE :abr");
        }

        $qb->andWhere('s.isValid = 1');


        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);
        $qb->addCriteria($criteria);

        $doctrinePaginator = new DoctrinePaginator($qb);
        return new Paginator($doctrinePaginator);
    }
}

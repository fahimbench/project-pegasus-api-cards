<?php

namespace App\Repository;

use App\Entity\CardType;
use App\Entity\MonsterType;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 30;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUrlToImage64($id)
    {
        $qb = $this->createQueryBuilder('c')
            ->setParameter('id', $id)
            ->where('c.id = :id')
            ->getQuery()
            ->getOneOrNullResult();

        if($qb === null){
            throw new NotFoundHttpException('image not found');
        }

        return $qb->getImg();
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function getCardFilter(Request $request): Paginator
    {

        if(!empty($request->getContent())){
            $content = json_decode($request->getContent());
            if(!empty($content->ids)){

                $qb = $this->createQueryBuilder('c');
                $andExpr = $qb->expr()->orX();
                foreach ($content->ids as $k => $v){
                    $qb->setParameter('type'.$k, $v->id);
                    $andExpr->add($qb->expr()->eq("c.id", ":type".$k));
                }
                $qb->andWhere($andExpr);

                $qb->andWhere('c.isValid = 1');

                $items_count = count($qb->getQuery()->getResult());

                $criteria = Criteria::create()
                    ->setFirstResult(0)
                    ->setMaxResults($items_count);
                $qb->addCriteria($criteria);

                $doctrinePaginator = new DoctrinePaginator($qb);
                return new Paginator($doctrinePaginator);
            }
        }
        $page = (int) $request->query->get('page', 1);
        $isMonster = (boolean) $request->query->get('is_monster', null);
        $isMagic = (boolean) $request->query->get('is_magic', null);
        $isTrap = (boolean) $request->query->get('is_trap', null);
        $name = (string) $request->query->get('name', null);
        $id = (string) $request->query->get('id', null);
        $atkmin = (integer) $request->query->get('attack_min', null);
        $atkmax = (integer) $request->query->get('attack_max', null);
        $defmin = (integer) $request->query->get('defense_min', null);
        $defmax = (integer) $request->query->get('defense_max', null);
        $lvlmin = (integer) $request->query->get('level_min', null);
        $lvlmax = (integer) $request->query->get('level_max', null);
        $lvlpmin = (integer) $request->query->get('scale_pendulum_min', null);
        $lvlpmax = (integer) $request->query->get('scale_pendulum_max', null);
        $linkmin = (integer) $request->query->get('link_min', null);
        $linkmax = (integer) $request->query->get('link_max', null);
        $attr = (array) $request->query->get('attribute', null);
        $icone = (array) $request->query->get('icone', null);
        $rarity = (array) $request->query->get('rarity', null);
        $type = (array) $request->query->get('card_type', null);
        $mtype = (array) $request->query->get('monster_type', null);

        $firstResult = ($page - 1) * self::ITEMS_PER_PAGE;

        $qb = $this->createQueryBuilder('c');

        if(!empty($type)){
            $qb->innerJoin('c.cardType', 'ct');
            $andExpr = $qb->expr()->orX();
            foreach ($type as $k => $v){
                $qb->setParameter('type'.$k, $v);
                $andExpr->add($qb->expr()->eq("ct.id", ":type".$k));
            }
            $qb->andWhere($andExpr);
        }
        if(!empty($mtype)){
            $qb->innerJoin('c.typeMonster', 'mt');
            $andExpr = $qb->expr()->orX();
            foreach ($mtype as $k => $v){
                $qb->setParameter('mtype'.$k, $v);
                $andExpr->add($qb->expr()->eq("mt.id", ":mtype".$k));
            }
            $qb->andWhere($andExpr);
        }

        if($isMonster){
            $qb
                ->setParameter('ismonster', $isMonster)
                ->orWhere('c.isMonster = :ismonster');
        }
        if($isMagic){
            $qb
                ->setParameter('ismagic', $isMagic)
                ->orWhere('c.isMagic = :ismagic');
        }
        if($isTrap){
            $qb
                ->setParameter('istrap', $isTrap)
                ->orWhere('c.isTrap = :istrap');
        }
        if(!empty($name)){
            $qb
                ->setParameter('name', '%'.$name.'%')
                ->andWhere("c.name LIKE :name");
        }
        if(!empty($id)){
            $qb
                ->setParameter('id', '%'.$id.'%')
                ->andWhere("c.idCard LIKE :id");
        }
        if($atkmin || $atkmax){
            if($atkmax > $atkmin){
                $qb
                    ->setParameter('atkmin', $atkmin)
                    ->setParameter('atkmax', $atkmax)
                    ->andWhere("c.attack >= :atkmin")
                    ->andWhere("c.attack <= :atkmax");
            }elseif($atkmax <= $atkmin){
                $qb
                    ->setParameter('atkmin', $atkmin)
                    ->andWhere("c.attack >= :atkmin");
            }
        }
        if($defmin || $defmax){
            if($defmax > $defmin){
                $qb
                    ->setParameter('defmin', $defmin)
                    ->setParameter('defmax', $defmax)
                    ->andWhere("c.defense >= :defmin")
                    ->andWhere("c.defense <= :defmax");
            }elseif($defmax <= $defmin){
                $qb
                    ->setParameter('defmin', $atkmin)
                    ->andWhere("c.defense >= :defmin");
            }
        }
        if($lvlmin || $lvlmax){
            if($lvlmax > $lvlmin){
                $qb
                    ->setParameter('lvlmin', $lvlmin)
                    ->setParameter('lvlmax', $lvlmax)
                    ->andWhere("c.level >= :lvlmin")
                    ->andWhere("c.level <= :lvlmax");
            }elseif($lvlmax <= $lvlmin){
                $qb
                    ->setParameter('lvlmin', $lvlmin)
                    ->andWhere("c.level >= :lvlmin");
            }
        }
        if($linkmin || $linkmax){
            if($linkmax > $linkmin){
                $qb
                    ->setParameter('linkmin', $linkmin)
                    ->setParameter('linkmax', $linkmax)
                    ->andWhere("c.linkLevel >= :linkmin")
                    ->andWhere("c.linkLevel <= :linkmax");
            }elseif($linkmax <= $linkmin){
                $qb
                    ->setParameter('linkmin', $lvlmin)
                    ->andWhere("c.linkLevel >= :linkmin");
            }
        }
        if($lvlpmin || $lvlpmax){
            if($lvlpmax > $lvlpmin){
                $qb
                    ->setParameter('lvlpmin', $lvlpmin)
                    ->setParameter('lvlpmax', $lvlpmax)
                    ->andWhere("c.pendulumScale >= :lvlpmin")
                    ->andWhere("c.pendulumScale <= :lvlpmax");
            }elseif($lvlpmax <= $lvlpmin){
                $qb
                    ->setParameter('lvlpmin', $lvlpmin)
                    ->andWhere("c.pendulumScale >= :lvlpmin");
            }
        }
        if(!empty($attr)){
            $andExpr = $qb->expr()->orX();
            foreach ($attr as $k => $v){
                $qb->setParameter('attr'.$k, $v);
                $andExpr->add($qb->expr()->eq("c.attribute", ":attr".$k));
            }
            $qb->andWhere($andExpr);
        }
        if(!empty($icone)){
            $andExpr = $qb->expr()->orX();
            foreach ($icone as $k => $v){
                $qb->setParameter('icone'.$k, $v);
                $andExpr->add($qb->expr()->eq("c.icone", ":icone".$k));
            }
            $qb->andWhere($andExpr);
        }
        if(!empty($rarity)){
            $andExpr = $qb->expr()->orX();
            foreach ($rarity as $k => $v){
                $qb->setParameter('rarity'.$k, $v);
                $andExpr->add($qb->expr()->eq("c.rarity", ":rarity".$k));
            }
            $qb->andWhere($andExpr);
        }

        $qb->andWhere('c.isValid = 1');


        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);
        $qb->addCriteria($criteria);

        $doctrinePaginator = new DoctrinePaginator($qb);
        return new Paginator($doctrinePaginator);
    }
}

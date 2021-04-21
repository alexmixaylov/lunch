<?php

namespace App\Repository;

use App\Entity\Dish;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;


class StatsRepository
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function countDishesByDate($date)
    {

        $ordersCounters = $this->em->createQueryBuilder()
                                   ->addSelect('o.counters')
                                   ->from(Order::class, 'o')
                                   ->innerJoin('o.menu', 'm')
                                   ->where('m.date = :date')
                                   ->setParameter('date', $date)
                                   ->getQuery()
                                   ->getResult();

        // оставь функциональщину для go и c. мы тут на пхп пишем, пхп это ООП уже много лет как
        //TODO можно переписать это дело в функциональном стиле

        $dishIdCntArr = [];
        foreach ($ordersCounters as $counter) {
            foreach ($counter['counters'] as $key => $item) {
                if (array_key_exists($key, $dishIdCntArr)) {
                    $dishIdCntArr[$key] = $dishIdCntArr[$key] + $item;
                } else {
                    $dishIdCntArr[$key] = $item;
                }
            }
        }


        $getDish = function ($dishId) {
            return $this->em->createQueryBuilder()
                            ->select('d.id')
                            ->addSelect('d.title')
                            ->addSelect('d.type')
                            ->from(Dish::class, 'd')
                            ->where('d.id = :dish_id')
                            ->setParameter('dish_id', $dishId)
                            ->getQuery()
                            ->getOneOrNullResult();
        };

        $result = [];
        foreach ($dishIdCntArr as $id => $cnt) {
            $dish     = $getDish($id);
            $result[] = [
                'dish_id' => $dish['id'],
                'title'   => $dish['title'],
                'type'    => $dish['type'],
                'cnt'     => $cnt
            ];
        }

        return $result;
    }

}

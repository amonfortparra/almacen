<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Picker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @author Alejandro
     * @param picker
     * 
     * Buscamos el pedido mas prioritario del picker
     */
    public function getPriorityOrderByPicker(picker $picker): ?Order
    {
        return $this->createQueryBuilder('o')
            ->where('o.picker = :id')
            ->setParameter('id', $picker)
            ->orderBy('o.final_date', 'ASC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
}

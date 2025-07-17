<?php

namespace App\Repository;

use App\Entity\Picker;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Picker>
 */
class PickerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picker::class);
    }

    public function getFreePicker(): array
    {
        $sql = 'SELECT p.id, p.name, COUNT(o.id) AS active_orders
            FROM App\Entity\Picker as p
            LEFT JOIN App\Entity\Order as o 
                WITH o.picker = p.id AND o.final_date >= CURRENT_DATE()
            GROUP BY p.id, p.name
            ORDER BY active_orders ASC, p.id ASC';
        $entity_manager = $this->getEntityManager();
        $query = $entity_manager->createQuery($sql)->setMaxResults(1);
        return $query->getOneOrNullResult();
    }
}

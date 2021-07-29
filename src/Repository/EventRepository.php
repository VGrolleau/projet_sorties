<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findSearch()
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->join('s.eventState', 'eStat');
        $queryBuilder->join('s.organizer', 'orga');
        $queryBuilder->addOrderBy('s.creationDate','DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}

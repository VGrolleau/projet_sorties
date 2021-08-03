<?php

namespace App\Repository;

use App\Data\LocationData;
use App\Data\SeachData;
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

    /**
     * @param SeachData $seachData
     * @return Event[]
     */
    public function findSearch(SeachData $seachData): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = $queryBuilder
                    ->leftJoin('s.eventState', 's_estat')
                        ->addSelect('s_estat')
                    ->leftJoin('s.organizer', 's_orga')
                        ->addSelect('s_orga')
                    ->leftJoin('s.users', 's_users')
                        ->addSelect('s_users')
                    ->addOrderBy('s.creationDate','DESC');
        if (!empty($seachData->campus)){
            $queryBuilder= $queryBuilder
                ->andWhere('s.campus IN (:campus)')
                ->setParameter('campus', $seachData->campus);
        }

        if (!empty($seachData->q)){
            $queryBuilder = $queryBuilder
                ->andWhere('s.name LIKE :q')
                ->setParameter('q', "%{$seachData->q}%");
        }

        if (!empty($seachData->sorties4)){
            $queryBuilder = $queryBuilder
                ->andWhere('s.eventState = 77');
            // changer l'id
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findInfosCreate()
    {
        $queryBuilder = $this->createQueryBuilder('ic');
        $queryBuilder->leftJoin('ic.location', 'loc')
            ->addSelect('loc');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}

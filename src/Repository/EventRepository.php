<?php

namespace App\Repository;

//use App\Data\LocationData;
use App\Data\SeachData;
use App\Entity\Event;
use App\Entity\EventState;
use App\Entity\User;
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
    public function findSearch(SeachData $seachData, User $user): array
    {
//        $date = \DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now'));
//        $date = new \DateTime();
        $eventState = 'EventState::class';

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

        if (!empty($seachData->start_Date)){
            $queryBuilder = $queryBuilder
                ->andWhere('s.startDate >= :start_Date')
                ->setParameter('start_Date', $seachData->start_Date);
        }

        if (!empty($seachData->end_Date)){
            $queryBuilder = $queryBuilder
                ->andWhere('s.startDate <= :end_Date')
                ->setParameter('end_Date', $seachData->end_Date);
        }

        if (!empty($seachData->sorties)){
            $userId = $user->getId();
            $queryBuilder = $queryBuilder
                ->andWhere('s.organizer = :userId')
                ->setParameter('userId', $userId);
        }

        if (!empty($seachData->sorties2)){
            $userId = $user->getId();
            $queryBuilder = $queryBuilder
                ->andWhere('s_users.id = :userId')
                ->setParameter('userId', $userId);
        }

        if (!empty($seachData->sorties3)){
            $userId = $user->getId();
            $queryBuilder = $queryBuilder
                ->andWhere('s_estat.name like \'Ouvert\'')
                ->andWhere('s_users.id != :userId')
                ->setParameter('userId', $userId);
        }

//        if ($criteria->getUserNoParticipant() === true) {
//            $userNoParticipant = $user->getId();
//            $qb2 = $this->createQueryBuilder('q');
//            $qb2->select('q.id');
//            $qb2->innerJoin('q.users', 'k', 'q.id = k.event_id');
//            $qb2->andWhere('k.id = :userId')
//                ->setParameter ('userId', $userNoParticipant);
//            // Exclut les events organisés par le user
//            $qb->andWhere('f.planner <> :userId')
//                ->setParameter ('userId', $userNoParticipant);
//            // On effectue une sous requête qui nous permet d'exclure les résultats de celle-ci (et donc les events
//            // auquel l'user participe, et par déduction on va avoir ceux auxquels il ne participe pas
//            $qb->andWhere($qb->expr()->notIn ('f.id', $qb2->getDQL()));
//            // On récupère les events ouverts
//            $qb->andWhere("f.stateEvent = 32");
//        }

//        if (!empty($seachData->sorties4)){
//            $queryBuilder = $queryBuilder
//                ->andWhere('s.eventState = 23');
//            // changer l'id
//        }

        if (!empty($seachData->sorties4)){
            $queryBuilder = $queryBuilder
                ->andWhere('s_estat.name like \'Terminé\'');
            // changer l'id
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findInfosCreate()
    {
        $queryBuilder = $this->createQueryBuilder('ic');
        $queryBuilder->leftJoin('ic.location', 'loc')
            ->addSelect('loc');
        $queryBuilder->leftJoin('ic.eventState', 'evsta')
            ->addSelect('evsta');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}

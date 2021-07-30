<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    // /**
    //  * @return City[] Returns an array of City objects
    //  */

    public function findByName($value)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c')
                     ->where('c.name like :name')
                     ->setParameter('name', "%{$value}%")
                     ->addOrderBy('c.name', 'ASC');

        $query=$queryBuilder->getQuery();


        //Commun aux deux


        $paginator = new Paginator($query);
        return $paginator;
    }


    /*
    public function findOneBySomeField($value): ?City
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

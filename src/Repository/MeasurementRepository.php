<?php

namespace App\Repository;

use App\Entity\Measurement;
use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Measurement>
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }
    /**
     * @return Measurement[]
     */
    public function findByLocation(Place $place): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.place = :place')
            ->andWhere('m.Date >= :today')
            ->setParameter('place', $place)
            ->setParameter('today', date('Y-m-d'))
            ->orderBy('m.Date', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Measurement[] Returns an array of Measurement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Measurement
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

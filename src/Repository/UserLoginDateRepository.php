<?php

namespace App\Repository;

use App\Entity\UserLoginDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserLoginDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLoginDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLoginDate[]    findAll()
 * @method UserLoginDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLoginDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLoginDate::class);
    }



    public function find_dates_by_id($id_user)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id_user = :val')
            ->setParameter('val', $id_user)
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function find_distinct_weeks($id_user)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id_user = :val')
            ->setParameter('val', $id_user)
            ->groupBy('u.week')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?UserLoginDate
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function DisplayAllBlogs()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT p.id,p.owner,p.title,p.Category,p.description FROM App\Entity\Blog p ");
        return $query->getResult();
    }

    public function findbylastsixdates()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.DateCreatedAt', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function DisplayBlogsOfUser($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function displayBaseOnCategory($id)
    {
        /*$entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT p FROM App\Entity\Blog p WHERE p.Category like :val ")
            ->setParameter('val', '%'.$data.'%');
        return $query->getResult();*/
        return $this->createQueryBuilder('b')
            ->andWhere('b.Category = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function DisplayOneBlog($data)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id = :val')
            ->setParameter('val', $data)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

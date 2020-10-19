<?php

namespace App\Repository;

use App\Entity\Tutorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tutorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tutorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tutorial[]    findAll()
 * @method Tutorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tutorial::class);
    }

     /**
      * @return Tutorial[] Returns an array of Tutorial objects
      */

    public function search($title)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.title LIKE :title')
            ->orWhere('t.description LIKE :title')
            ->having('t.validation = 1')
            ->setParameter('title', '%'.$title.'%')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * TODO:à terminer
     * @return Tutorial[] Returns an array of Tutorial objects
     */

    public function searchplus($title, $duration, $level, $cost)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.title LIKE :title')
            ->orWhere('t.description LIKE :title')
            ->having('t.validation = 1')
            ->andWhere('t.duration = :duration')
            ->setParameter('title', '%'.$title.'%')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->execute()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Tutorial
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

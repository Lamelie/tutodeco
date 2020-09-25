<?php

namespace App\Repository;

use App\Entity\UserTutorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTutorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTutorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTutorial[]    findAll()
 * @method UserTutorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTutorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTutorial::class);
    }

    // /**
    //  * @return UserTutorial[] Returns an array of UserTutorial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTutorial
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

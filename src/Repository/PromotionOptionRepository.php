<?php

namespace App\Repository;

use App\Entity\PromotionOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PromotionOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionOption[]    findAll()
 * @method PromotionOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionOption::class);
    }

    // /**
    //  * @return PromotionOption[] Returns an array of PromotionOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromotionOption
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

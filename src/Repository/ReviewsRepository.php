<?php

namespace App\Repository;

use App\Entity\Reviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reviews[]    findAll()
 * @method Reviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reviews::class);
    }

    // /**
    //  * @return Reviews[] Returns an array of Reviews objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function userHasReview($brandId,$userId): ?Reviews
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.brand_id = :brand')
            ->andWhere('r.user_id = :user')
            ->setParameters(array('brand' => $brandId, 'user' => $userId))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}

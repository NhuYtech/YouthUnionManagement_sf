<?php

namespace App\Repository;

use App\Entity\Recognition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recognition>
 */
class RecognitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recognition::class);
    }

    //    /**
//     * @return Recognition[] Returns an array of Recognition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findByUserUnitName(?string $unitName): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.user', 'u')
            ->addSelect('u');

        if ($unitName) {
            $qb->where('u.unitName = :unitName')
                ->setParameter('unitName', $unitName);
        }

        return $qb->getQuery()->getResult();
    }

}

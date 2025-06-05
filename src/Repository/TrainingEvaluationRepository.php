<?php

namespace App\Repository;

use App\Entity\TrainingEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingEvaluation>
 */
class TrainingEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingEvaluation::class);
    }

    //    /**
    //     * @return TrainingEvaluation[] Returns an array of TrainingEvaluation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }


    public function findByUserUnitName(?string $unitName): array
    {
        $qb = $this->createQueryBuilder('te')
            ->leftJoin('te.user', 'u')
            ->addSelect('u');

        if ($unitName) {
            $qb->where('u.unitName = :unitName')
                ->setParameter('unitName', $unitName);
        }

        return $qb->getQuery()->getResult();
    }

}

<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    /**
     * Đếm số sự kiện theo từng tháng
     */
    public function countEventsPerMonth(): array
    {
        // Query Builder để đếm số sự kiện theo tháng từ trường startDate
        $qb = $this->createQueryBuilder('e')
            ->select('MONTH(e.startDate) AS month', 'COUNT(e.id) AS count')
            ->groupBy('month')
            ->orderBy('month', 'ASC');

        // Trả về kết quả dưới dạng mảng
        return $qb->getQuery()->getResult();
    }
}

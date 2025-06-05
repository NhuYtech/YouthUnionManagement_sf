<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;
final class StatisticsController extends AbstractController
{

    #[Route('/api/statistics/events-per-month', name: 'events_per_month', methods: ['GET'])]
    public function getEventsPerMonth(EntityManagerInterface $entityManager)
    {
        // Truy vấn tất cả các sự kiện
        $events = $entityManager->getRepository(Event::class)->findAll();

        // Tạo mảng đếm sự kiện theo tháng
        $monthCounts = array_fill(1, 12, 0); // Khởi tạo mảng với 12 tháng, giá trị ban đầu là 0
        $currentYear = (int) date('Y');

        foreach ($events as $event) {
            $date = $event->getStartDate();
            if ($date instanceof \DateTimeInterface) {
                $month = (int) $date->format('n'); // tháng từ 1 đến 12
                $monthCounts[$month]++;
            }
        }

        // Chuẩn bị dữ liệu JSON
        $data = [];
        foreach ($monthCounts as $month => $count) {
            $data[] = [
                'month' => $month,
                'count' => $count,
            ];
        }

        // Trả về dữ liệu dạng JSON
        return new JsonResponse($data);
    }
}

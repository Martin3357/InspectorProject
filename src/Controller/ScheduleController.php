<?php

namespace App\Controller;

use App\Service\ScheduleService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * @Route("api/schedule", name="app_schedule")
     */
    public function index(): JsonResponse
    {
        $schedules = $this->scheduleService->getAllSchedules();
        return $this->json($schedules);
    }
    /**
     * @Route("api/schedule/{id}", name="get_schedule", methods={"GET"})
     */
    public function getSchedule(int $id): JsonResponse
    {
        $schedule = $this->scheduleService->getScheduleById($id);
        if (!$schedule) {
            return new JsonResponse(['error' => 'Schedule not found'], 404);
        }
        return new JsonResponse($schedule);
    }

    /**
     * @Route("api/schedule/{id}", name="delete_schedule", methods={"DELETE"})
     */
    public function deleteSchedule(int $id): JsonResponse
    {
        $this->scheduleService->deleteSchedule($id);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

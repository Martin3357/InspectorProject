<?php

namespace App\Controller;

use App\Entity\Inspector;
use App\Entity\Job;
use App\Service\ScheduleService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/api/schedule/add", name="add_schedule", methods={"POST"})
     */
    public function addSchedule(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Validate request data
        if (!isset($requestData['job_id']) || !isset($requestData['inspector_id'])) {
            return new JsonResponse(['message' => 'job_id and inspector_id are required'], Response::HTTP_BAD_REQUEST);
        }

        $jobId = $requestData['job_id'];
        $inspectorId = $requestData['inspector_id'];

        $inspector = $this->getDoctrine()->getRepository(Inspector::class)->find($inspectorId);
        $job = $this->getDoctrine()->getRepository(Job::class)->find($jobId);

        if (!$inspector) {
            return new JsonResponse(['message' => 'Inspector not found'], Response::HTTP_NOT_FOUND);
        }

        if(!$job){
            return new JsonResponse(['message' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        $responseMessage = $this->scheduleService->addSchedule($jobId, $inspector);

        if ($responseMessage === null) {
            return new JsonResponse(['message' => 'Schedule added successfully'], Response::HTTP_CREATED);
        } else {
            return new JsonResponse(['message' => $responseMessage], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @Route("api/schedule/{id}", name="delete_schedule", methods={"DELETE"})
     */
    public function deleteSchedule(int $id): JsonResponse
    {
        $schedule = $this->scheduleService->deleteSchedule($id);
        return new JsonResponse(['error'=> $schedule],404);
    }
}

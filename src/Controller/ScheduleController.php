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
     * Get all schedules.
     *
     * @Route("api/schedule", name="app_schedule", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        
        $schedules = $this->scheduleService->getAllSchedules();

        return $this->json($schedules);
    }

    /**
     * Get a specific schedule by ID.
     *
     * @Route("api/schedule/{id}", name="get_schedule", methods={"GET"})
     */
    public function getSchedule(int $id): JsonResponse
    {
        
        $schedule = $this->scheduleService->getScheduleById($id);

        // Check if the schedule exists
        if (!$schedule) {
            return new JsonResponse(['error' => 'Schedule not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($schedule);
    }

    /**
     * Adds a new schedule.
     *
     * @Route("/api/schedule", name="add_schedule", methods={"POST"})
     */
    public function addSchedule(Request $request): JsonResponse
    {

        $requestData = json_decode($request->getContent(), true);
        
        if (!isset($requestData['job_id']) || !isset($requestData['inspector_id'])) {
            
            return new JsonResponse(['message' => 'job_id and inspector_id are required'], Response::HTTP_BAD_REQUEST);
        }

        // Extract job ID and inspector ID from the request data
        $jobId = $requestData['job_id'];
        $inspectorId = $requestData['inspector_id'];

        // Retrieve the inspector and job entities from the database
        $inspector = $this->getDoctrine()->getRepository(Inspector::class)->find($inspectorId);
        $job = $this->getDoctrine()->getRepository(Job::class)->find($jobId);

        // Check if inspector and job entities exist
        if (!$inspector) {
            
            return new JsonResponse(['message' => 'Inspector not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$job) {
            
            return new JsonResponse(['message' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        
        $responseMessage = $this->scheduleService->addSchedule($jobId, $inspector);

        // Check if schedule was added successfully
        if ($responseMessage === null) {
            
            return new JsonResponse(['message' => 'Schedule added successfully'], Response::HTTP_CREATED);
        } else {
            
            return new JsonResponse(['message' => $responseMessage], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Deletes a schedule by ID.
     *
     * @Route("api/schedule/{id}", name="delete_schedule", methods={"DELETE"})
     */
    public function deleteSchedule(int $id): JsonResponse
    {
        
        $scheduleMessage = $this->scheduleService->deleteSchedule($id);

        return new JsonResponse(['message'=> $scheduleMessage]);
    }

    /**
     * Update the schedule when a job is completed and change
     * the status of the job to completed
     * @Route("api/schedule/{id}", name="update_schedule", methods={"PUT"})
     */
    public function jobCompletion(Request $request, int $id): JsonResponse
    {
        
        $requestData = json_decode($request->getContent(), true);
        $completed = $this->scheduleService->completeJob($id, $requestData);

        if (!$completed) {
            return new JsonResponse(['message' => 'You can\'t perform this action'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($completed);
    }
}

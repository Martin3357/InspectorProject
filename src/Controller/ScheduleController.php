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
     * Fetches all schedules.
     *
     * @Route("api/schedule", name="app_schedule", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        // Fetch all schedules from the service
        $schedules = $this->scheduleService->getAllSchedules();

        // Return the schedules as JSON response
        return $this->json($schedules);
    }

    /**
     * Fetches a specific schedule by ID.
     *
     * @Route("api/schedule/{id}", name="get_schedule", methods={"GET"})
     */
    public function getSchedule(int $id): JsonResponse
    {
        // Retrieve the schedule by ID from the service
        $schedule = $this->scheduleService->getScheduleById($id);

        // Check if the schedule exists
        if (!$schedule) {
            // If not found, return a 404 response with an error message
            return new JsonResponse(['error' => 'Schedule not found'], Response::HTTP_NOT_FOUND);
        }

        // Return the schedule as JSON response
        return $this->json($schedule);
    }

    /**
     * Adds a new schedule.
     *
     * @Route("/api/schedule", name="add_schedule", methods={"POST"})
     */
    public function addSchedule(Request $request): JsonResponse
    {
        // Decode the request content into an associative array
        $requestData = json_decode($request->getContent(), true);

        // Validate if required data is provided
        if (!isset($requestData['job_id']) || !isset($requestData['inspector_id'])) {
            // If validation fails, return a 400 response with an error message
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
            // If inspector not found, return a 404 response with an error message
            return new JsonResponse(['message' => 'Inspector not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$job) {
            // If job not found, return a 404 response with an error message
            return new JsonResponse(['message' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        // Add the schedule using the ScheduleService
        $responseMessage = $this->scheduleService->addSchedule($jobId, $inspector);

        // Check if schedule was added successfully
        if ($responseMessage === null) {
            // If successful, return a 201 response with a success message
            return new JsonResponse(['message' => 'Schedule added successfully'], Response::HTTP_CREATED);
        } else {
            // If failed, return a 400 response with an error message
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
        // Delete the schedule using the ScheduleService
        $scheduleMessage = $this->scheduleService->deleteSchedule($id);

        // Return a JSON response with a message indicating the result of the operation
        return new JsonResponse(['message'=> $scheduleMessage]);
    }

    /**
     * Marks a job as completed.
     *
     * @Route("api/schedule/{id}", name="update_schedule", methods={"PUT"})
     */
    public function jobCompletion(Request $request, int $id): JsonResponse
    {
        // Decode the request content into an associative array
        $requestData = json_decode($request->getContent(), true);

        // Complete the job using the ScheduleService
        $completed = $this->scheduleService->completeJob($id, $requestData);

        // Check if the completion result is a JsonResponse (error response)
        if (!$completed) {
            return new JsonResponse(['message' => 'You can\'t perform this action'], Response::HTTP_BAD_REQUEST);
        }

        // If successful, return the completion result as JSON response
        return $this->json($completed);
    }
}

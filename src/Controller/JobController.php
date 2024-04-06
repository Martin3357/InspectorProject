<?php
// src/Controller/JobController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JobService;
use Symfony\Component\HttpFoundation\JsonResponse;

class JobController extends AbstractController
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }
    

    /**
     * Get all jobs
     * 
     * @Route("api/jobs", name="job_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $jobs = $this->jobService->getAllJobs();
        return $this->json($jobs, 200, [], ['groups' => 'inspector:read']);
    }

    /**

     * @Route("api/jobs", name="job_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $job = $this->jobService->createJob($data);

        return $this->json($job);
    }

    /**
     * @Route("api/jobs/{id}", name="job_show", methods={"GET"})
     */
    public function show($id): Response
    {
        $job = $this->jobService->getJobById($id);

        if (!$job) {
            throw $this->createNotFoundException('Job not found');
        }

        return $this->json($job);
    }

    /**
     * @Route("api/jobs/{id}", name="job_update", methods={"PUT"})
     */
    public function update(Request $request, $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $job = $this->jobService->updateJob($id, $data);

        if (!$job) {
            throw $this->createNotFoundException('Job not found');
        }

        return $this->json($job);
    }

    /**
     * @Route("api/jobs/{id}", name="job_delete", methods={"DELETE"})
     */
    public function delete($id): Response
    {
        $this->jobService->deleteJob($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("api/jobs/by-status/{status}", name="job_by_status", methods={"GET"})
     */
    public function getByStatus(string $status): JsonResponse
    {
        $jobs = $this->jobService->getJobsByStatus($status);

        if (empty($jobs)) {
            throw $this->createNotFoundException('No jobs found with status: '.$status);
        }

        return new JsonResponse($jobs);
    }

}

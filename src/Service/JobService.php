<?php 
// src/Service/JobService.php
namespace App\Service;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JobRepository;

class JobService
{
    private $entityManager;

    private $jobRepository;


    public function __construct(EntityManagerInterface $entityManager,JobRepository $jobRepository)
    {
        $this->entityManager = $entityManager;
    
        $this->jobRepository = $jobRepository;
    }

    /**
     * Get all jobs
     *
     * @return array
     */
    public function getAllJobs(): array
    {
        $jobs = $this->entityManager->getRepository(Job::class)->findAll();
        $responseData = [];
        foreach ($jobs as $job) {
            $responseData[] = $this->serializeJob($job);
        }
        return $responseData;
    }

    /**
     * Formated data of a Job
     *
     * @param Job $job
     * @return array
     */
    private function serializeJob(Job $job): array
    {
        return [
            'id' => $job->getId(),
            'description' => $job->getDescription(),
            'status' => $job->getStatus(),
        ];
    }

    /**
     * Create a Job with the data that are send from
     * request
     * @param array $data
     * @return Job
     */
    public function createJob(array $data): Job
    {
        $job = new Job();
        $job->setDescription($data['description']);
        $job->setStatus($data['status'] ?? 'To Do');

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }

    /**
     * Get one job by the id
     *
     * @param integer $id
     * @return array|null
     */
    public function getJobById(int $id): ?array
    {
        $job =  $this->entityManager->getRepository(Job::class)->find($id);
        if (!$job) {
            return null;
        }
        return $this->serializeJob($job);
    }

    /**
     * Update a Job by id
     *
     * @param integer $id
     * @param array $data
     * @return Job|null
     */
    public function updateJob(int $id, array $data): ?Job
    {
        $job = $this->entityManager->getRepository(Job::class)->find($id);

        if (!$job) {
            return null;
        }

        $job->setDescription($data['description']);
        $job->setStatus($data['status']);

        $this->entityManager->flush();

        return $job;
    }

    /**
     * Delete the job
     *
     * @param integer $id
     * @return void
     */
    public function deleteJob(int $id): void
    {
        $job = $this->entityManager->getRepository(Job::class)->find($id);

        if ($job) {
            $this->entityManager->remove($job);
            $this->entityManager->flush();
        }
    }

    
    /**
     * Return the jobs by their status
     *
     * @param string $status
     */
    public function getJobsByStatus(string $status)
    {
        $job = $this->jobRepository->findJobByStatus($status);
        if (!$job) {
            return null;
        }

        return $this->serializeJob($job);
    }
    
}

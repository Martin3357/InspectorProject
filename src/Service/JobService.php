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

    public function getAllJobs(): array
    {
        $jobs = $this->entityManager->getRepository(Job::class)->findAll();
        $responseData = [];
        foreach ($jobs as $job) {
            $responseData[] = $this->serializeJob($job);
        }
        return $responseData;
    }
    private function serializeJob(Job $job): array
    {
        return [
            'id' => $job->getId(),
            'description' => $job->getDescription(),
            'status' => $job->getStatus(),
            // Add other properties as needed
        ];
    }

    public function createJob(array $data): Job
    {
        $job = new Job();
        $job->setDescription($data['description']);
        $job->setStatus($data['status'] ?? 'To Do');

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }

    public function getJobById(int $id): ?array
    {
        $job =  $this->entityManager->getRepository(Job::class)->find($id);
        if (!$job) {
            return null;
        }
        return $this->serializeJob($job);
    }

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

    public function deleteJob(int $id): void
    {
        $job = $this->entityManager->getRepository(Job::class)->find($id);

        if ($job) {
            $this->entityManager->remove($job);
            $this->entityManager->flush();
        }
    }

    
    public function getJobsByStatus(string $status)
    {
        $job = $this->jobRepository->findJobByStatus($status);
        if (!$job) {
            return null;
        }

        return $this->serializeJob($job);
    }
    

}

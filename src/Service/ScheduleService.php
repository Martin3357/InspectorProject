<?php

namespace App\Service;

use App\Entity\Inspector;
use App\Entity\Job;
use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\InspectorService;
use App\Service\JobService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ScheduleService
{
    private $entityManager;
    private $inspectorService; // Add InspectorService property

    private $jobService;

    public function __construct(EntityManagerInterface $entityManager, InspectorService $inspectorService, JobService $jobService)
    {
        $this->entityManager = $entityManager;
        $this->inspectorService = $inspectorService; 
        $this->jobService = $jobService; 
    }

    /**
     * Get all the schedules from the 
     * database
     *
     * @return array
     */
    public function getAllSchedules(): array
    {
        $schedules = $this->entityManager->getRepository(Schedule::class)->findAll();
        $responseData = [];
        foreach ($schedules as $schedule) {
            $responseData[] = $this->serializeSchedule($schedule);
        }
        return $responseData;
    }

    /**
     * Formated data 
     *
     * @param Schedule $schedule
     * @return array
     */
    private function serializeSchedule(Schedule $schedule): array
    {
        $inspector = $this->inspectorService->getInspectorById($schedule->getInspector()->getId());
        $job = $this->jobService->getJobById($schedule->getJob()->getId());
        return [
            'id' => $schedule->getId(),
            'inspector' => $inspector,
            'job' => $job,
        ];
    }

    /**
     * Get one schedule by their id
     *
     * @param integer $id
     * @return array|null
     */
    public function getScheduleById(int $id): ?array
    {

        $schedule = $this->entityManager->getRepository(Schedule::class)->find($id);
        if (!$schedule) {
            return null;
        }
        return $this->serializeSchedule($schedule);
    }

    /**
     * Add a schedule for an inspector
     * for given job
     *
     * @param integer $jobId
     * @param Inspector $inspector
     * @return string|null
     */
    public function addSchedule(int $jobId, Inspector $inspector): ?string
    {
        $job = $this->entityManager->getRepository(Job::class)->find($jobId);

        // Check if job status allows scheduling
        if ($job->getStatus() !== 'To Do') {
            return 'Job is already assigned to another inspector';
        }


        $this->entityManager->persist($job);
        // Create a new schedule
        $schedule = new Schedule();
        $schedule->setJob($job);
        $schedule->setInspector($inspector);
        $schedule->setAssignedAt($this->setTimezone($inspector, new \Datetime()));

        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        // Update job status to "In Progress"
        $job->setStatus('In Progress');

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return null;
    }


    /**
     * Update the schedule set complete and 
     * change the job status
     * @param integer $id
     * @param array $data
     * @return Schedule|null
     */
    public function completeJob(int $id, array $data = []): ?Schedule
    {
        $schedule = $this->entityManager->getRepository(Schedule::class)->find($id);
        
        $job = $schedule->getJob();
        $inspector = $schedule->getInspector(); 

        if(!$this->isJobInProgressAndBelongsToInspector($job, $inspector, $data)){
            return null;
        }

        $job->setStatus('Completed');

        $updatedSchedule = $this->updateSchedule($id, $data, $inspector);

        return $updatedSchedule;
    
    }

    /**
     * Check for the job
     *
     * @param Job $job
     * @param Inspector $inspector
     * @param array $data
     * @return boolean
     */
    private function isJobInProgressAndBelongsToInspector(Job $job, Inspector $inspector, array $data): bool
    {
        
        if($job->getStatus() == 'In Progress' && $inspector->getId() == $data['inspector_id']){
            return true;
        }

        return false;
    }

    /**
     * Update the schedule and set the note
     * that are given from the request
     * @param integer $id
     * @param array $data
     * @param Inspector $inspector
     * @return Schedule|null
     */
    public function updateSchedule(int $id, array $data = [], Inspector $inspector): ?Schedule
    {
        
        $schedule = $this->entityManager->getRepository(Schedule::class)->find($id);


        $schedule->setCompletedAt($this->setTimezone($inspector, new \Datetime()));
        $schedule->setNote($data['note'] ?? $schedule->getNote());
        
        $this->entityManager->flush();

        return $schedule;
    }

    
    /**
     * Delete the schedule and change the job status 
     *
     * @param integer $id
     */
    public function deleteSchedule(int $id): ?String
    {
        
        $schedule = $this->entityManager->getRepository(Schedule::class)->find($id);

        if (!$schedule) {
            return 'Schedule not found';
        }

        // Check if schedule is completed
        if ($schedule->getCompletedAt() !== null) {
            return 'Cannot delete completed schedule';
        }

        // Check if related job status is "Completed"
        $job = $schedule->getJob();
        if ($job && $job->getStatus() === "Completed") {
            return 'Cannot delete schedule associated with a completed job';
        }

        // Delete the schedule
        $this->entityManager->remove($schedule);
        $this->entityManager->flush();

        // Update job status to "To Do"
        if ($job) {
            $job->setStatus("To Do");
            $this->entityManager->persist($job);
            $this->entityManager->flush();
        }

        return 'Deleted Successfully';
    }

    /**
     * Get the timezone by their locations
     * @param Inspector $inspector
     * @param \DateTime $assignedTime
     * @return \DateTime
     */
    private function setTimezone(
        Inspector $inspector,
        \DateTime $assignedTime
    ): \DateTime
    {
        $timezone = new \DateTime();
        $timezone->format('Y-m-d H:i:s');
        $location = $inspector->getLocation();
        if ($location == "Spain") {
            $timezone = $assignedTime->modify('+2 hours');
        } else if ($location == "Mexico") {
            $timezone = $assignedTime->modify('-6 hours');
        } else if ($location == "India") {
            $timezone = $assignedTime->modify('+5.5 hours');
        }
        return $timezone;
    }
}


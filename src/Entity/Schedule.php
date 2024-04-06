<?php
// src/Entity/Schedule.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inspector", inversedBy="schedules", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $inspector;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="schedules", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @ORM\Column(type="datetime")
     */
    private $assignedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $completedAt;

    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInspector(): ?Inspector
    {
        return $this->inspector;
    }

    public function setInspector(?Inspector $inspector): self
    {
        $this->inspector = $inspector;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function getJobId(): ?int
    {
        return $this->getJob()->getId();
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getAssignedAt(): ?\DateTimeInterface
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(\DateTimeInterface $assignedAt): self
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeInterface $completedAt): self
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    
    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;
        return $this;
    }
}

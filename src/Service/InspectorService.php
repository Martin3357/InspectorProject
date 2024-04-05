<?php 

namespace App\Service;

use App\Entity\Inspector;
use Doctrine\ORM\EntityManagerInterface;

class InspectorService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllInspectors(): array
    {
        $inspectors = $this->entityManager->getRepository(Inspector::class)->findAll();
        $responseData = [];
        foreach ($inspectors as $inspector) {
            $responseData[] = $this->serializeInspector($inspector);
        }
        return $responseData;
    }

    public function getInspectorById(int $id): ?array
    {
        $inspector = $this->entityManager->getRepository(Inspector::class)->find($id);
        if (!$inspector) {
            return null;
        }
        return $this->serializeInspector($inspector);
    }

    private function serializeInspector(Inspector $inspector): array
    {
        return [
            'id' => $inspector->getId(),
            'name' => $inspector->getName(),
            // Add other properties as needed
        ];
    }
}

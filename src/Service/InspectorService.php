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

    /**
     * Return an array of all inspectors from the repository
     *
     * @return array
     */
    public function getAllInspectors(): array
    {
        $inspectors = $this->entityManager->getRepository(Inspector::class)->findAll();
        $responseData = [];
        foreach ($inspectors as $inspector) {
            $responseData[] = $this->serializeInspector($inspector);
        }
        return $responseData;
    }

    /**
     * Return data of an inspector
     *
     * @param integer $id
     * @return array|null
     */
    public function getInspectorById(int $id): ?array
    {
        $inspector = $this->entityManager->getRepository(Inspector::class)->find($id);
        if (!$inspector) {
            return null;
        }
        return $this->serializeInspector($inspector);
    }

    /**
     * Return formated data of inspector
     *
     * @param Inspector $inspector
     * @return array
     */
    private function serializeInspector(Inspector $inspector): array
    {
        return [
            'id' => $inspector->getId(),
            'name' => $inspector->getName(),
        ];
    }
}

<?php

namespace App\Controller;

use App\Entity\Inspector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\InspectorService;
class InspectorController extends AbstractController
{
    private $inspectorService;

    public function __construct(InspectorService $inspectorService)
    {
        $this->inspectorService = $inspectorService;
    }

    /**
     * @Route("/api/inspectors", name="get_inspectors", methods={"GET"})
     */
    public function getInspectors(): JsonResponse
    {
        $inspectors = $this->inspectorService->getAllInspectors();
        return $this->json($inspectors, 200, [], ['groups' => 'inspector:read']);
    }

    /**
     * @Route("/api/inspectors/{id}", name="api_get_inspector", methods={"GET"})
     */
    public function getInspector(int $id): JsonResponse
    {
        $inspector = $this->inspectorService->getInspectorById($id);
        if (!$inspector) {
            return new JsonResponse(['error' => 'Inspector not found'], 404);
        }
        return new JsonResponse($inspector);
    }
}

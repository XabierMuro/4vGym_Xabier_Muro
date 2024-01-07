<?php

namespace App\Controller;

use App\Entity\ActivityTypes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ActivityTypeController extends AbstractController
{
    #[Route('/activity-types', name: 'app_activity_type', format: 'json', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(ActivityTypes::class);

        $activityTypes = $repository->findAll();

        $response = [];
        foreach ($activityTypes as $activityType) {
            $response[] = [
                'id' => $activityType->getId(),
                'name' => $activityType->getName(),
                'number_monitors' => $activityType->getNumberMonitors(),
            ];
        }

        return $this->json($response);
    }

    
}

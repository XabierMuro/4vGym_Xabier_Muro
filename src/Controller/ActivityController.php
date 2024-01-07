<?php

namespace App\Controller;

use App\Entity\Activities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class ActivityController extends AbstractController
{
    #[Route('/activities', name: 'activity_get', format: 'json', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Activities::class);

        $activities = $repository->findAll();

        $response = [];
        foreach ($activities as $activity) {
            $response[] = [
                'activity_type' => $activity->getActivityType(),
                'beggining_date' => $activity->getBegginingDate(),
                'end_date' => $activity->getEndDate(),
                'activityMonitors' => $activity->getActivityMonitors(),
            ];
        }

        return $this->json($response);
    }

    #[Route('/activities', name: 'activity_post', format: 'json', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $activities = new Activities();
        $activities->setActivityType($data['activity_type'] ?? 'pilates');
        $activities->setBegginingDate($data['beggining_date'] ?? 16/12/2021);
        $activities->setEndDate($data['end_date'] ?? 17/12/2021);
        $activities->setActivityMonitors($data['activityMonitors'] ?? 'evaristo123@gmail.com');

        $entityManager->persist($activities);
        $entityManager->flush();

        $response = [
            'data' => [
                'activity_type' => $activities->getActivityType(),
                'beggining_date' => $activities->getBegginingDate(),
                'end_date' => $activities->getEndDate(),
                'activityMonitors' => $activities->getActivityMonitors(),
            ],
        ];

        return $this->json($response, Response::HTTP_CREATED);
    }
    #[Route('/activities/{id}', name: 'activity_put', format: 'json', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        
        $activity = $entityManager->getRepository(Activities::class)->find($id);

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $activity->setActivityType($data['activity_type']);
        }

        if (isset($data['telephone'])) {
            $activity->setBegginingDate($data['beggining_date']);
        }

        if (isset($data['email'])) {
            $activity->setEndDate($data['end_date']);
        }

        if (isset($data['photo'])) {
            $activity->setActivityMonitors($data['activityMonitors']);
        }

        $entityManager->flush();

        $response = [
            'data' => [
                'activity_type' => $activity->getActivityType(),
                'beggining_date' => $activity->getBegginingDate(),
                'end_date' => $activity->getEndDate(),
                'activityMonitors' => $activity->getActivityMonitors(),
            ],
        ];

        return $this->json($response);
    }

    #[Route('/activities/{id}', name: 'activity_delete', format: 'json', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        $activity = $entityManager->getRepository(Activities::class)->find($id);

        $entityManager->remove($activity);

        $entityManager->flush();

        return $this->json(['message' => 'activity deleted']);
    }
}

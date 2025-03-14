<?php

namespace App\Controller;

use App\Entity\OKR;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class OKRController extends AbstractController
{
    #[Route('/okr', name: 'app_okr')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OKRController.php',
        ]);
    }

    #[Route('/okr/{id}', name: 'get_okr', methods: ["GET"])]
    public function get(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $okr = $entityManager->getRepository(OKR::class)->find($id);

        if(!$okr) {
            throw $this->createNotFoundException(
                'No OKR found with id'.$id
            );
        }

        return $this->json([
            'message' => 'success',
            'data' => $okr->getOkr()
        ]);
    }

    #[Route('/okr/new', name: 'post_okr', methods: ["POST"])]
    public function post(EntityManagerInterface $entityManager): JsonResponse
    {
        $defaultValue = "{\"id\":0,\"data\":{\"objective\":\"Objective\",\"description\":\"description\"}}";

        $okr = new OKR();
        $okr->setOkr($defaultValue);
        $entityManager->persist($okr);
        $entityManager->flush();

        return $this->json([
            'message' => 'success',
            'data' => [
                'id' => $okr->getId()
            ]
            ]);
    }
}

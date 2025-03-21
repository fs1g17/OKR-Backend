<?php

namespace App\Controller;

use App\Entity\OKR;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class OKRController extends AbstractController
{
    #[Route('/api/okr/list', name: 'list_okr', methods: ["GET"])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();

        $okrRepository = $entityManager->getRepository(OKR::class);
        $okrs = $okrRepository->findByCreatedBy($user);

        $okrStrings = array_map(fn(OKR $okr) => ['id' => $okr->getId(), 'name' => $okr->getname()], $okrs);

        return $this->json([
            'message' => 'Success',
            'data' => $okrStrings
        ]);
    }

    #[Route('/api/okr/new', name: 'post_okr', methods: ["POST"])]
    public function post(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data['name'])) {
            return $this->json([
                'message' => 'error',
                'description' => 'provide a name for the OKR'
            ]);
        }

        $defaultValue = "{\"counter\":1,\"data\":{\"id\":0,\"data\":{\"objective\":\"Objective\",\"description\":\"Description\"},\"children\":[]}}";

        $user = $this->getUser();

        $okr = new OKR();
        $okr->setOkr($defaultValue);
        $okr->setCreatedBy($user);
        $okr->setName($data['name']);

        $entityManager->persist($okr);
        $entityManager->flush();

        return $this->json([
            'message' => 'success',
            'data' => [
                'id' => $okr->getId(),
                'name' => $okr->getName(),
                'okr' => json_decode($okr->getOkr(), true)
            ]
        ]);
    }

    #[Route('/api/okr/{id}', name: 'get_okr', methods: ["GET"])]
    public function get(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $okr = $entityManager->getRepository(OKR::class)->find($id);

        if (!$okr) {
            throw $this->createNotFoundException(
                'No OKR found with id' . $id
            );
        }

        $currentUserIdentifier = $this->getUser()->getUserIdentifier();
        if ($okr->getCreatedBy()->getUserIdentifier() !== $currentUserIdentifier){
            throw $this->createAccessDeniedException("Access denied for user $currentUserIdentifier");
        }

        return $this->json([
            'message' => 'success',
            'data' => [
                'name' => $okr->getName(),
                'okr' => json_decode($okr->getOkr(), true),
            ]
        ]);
    }

    #[Route(path: '/api/okr/{id}', name: 'update_okr', methods: ["POST"])]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $okr = $entityManager->getRepository(OKR::class)->find($id);

        if (!$okr) {
            throw $this->createNotFoundException(
                'No OKR found with id' . $id
            );
        }

        $jsonString = $request->getContent();
        $data = json_decode($jsonString, true);
        if($data === null) {
            return $this->json([
                'message' => 'error',
                'description' => 'Failed to parse OKR - invalid JSON'
            ], 400);
        }

        $okr->setOkr($jsonString);
        $entityManager->persist($okr);
        $entityManager->flush();

        return $this->json([
            'message' => 'success',
            'data' => [
                'id' => $okr->getId(),
                'name' => $okr->getName(),
                'okr' => json_decode($okr->getOkr(), true)
            ]
        ]);
    }
}

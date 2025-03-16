<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration', methods: ['POST'])]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Validate required fields
        if (empty($data['username']) || empty($data['password']) || empty($data['confirm_password'])) {
            return $this->json(['message' => 'error', 'description' => 'Username and password are required.'], 400);
        }
        if ($data['password'] !== $data['confirm_password']) {
            return $this->json(['message' => 'error', 'description' => 'passwords must match']);
        }

        $user = new User();
        $user->setUsername($data['username']);

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            return $this->json([
                'message' => 'error',
                'description' => 'Username is already taken'
            ]);
        } catch (Exception $e) {
            return $this->json([
                'message' => 'error',
                'description' => $e->getMessage()
            ]);
        }

        return $this->json([
            'message' => 'success',
        ], 201);
    }
}

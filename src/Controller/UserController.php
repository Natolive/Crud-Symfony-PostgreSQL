<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;

class UserController extends AbstractController
{
    // private Serializer $serializer;
    private EntityRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    /**
     * Method to find all users
     * @return JsonResponse
     */
    #[Route('/user/findAll', name: 'user_findall', methods: ['GET'])]
    public function findAllUsers(): JsonResponse
    {
        try {
            $users = $this->userRepository->findAll();

            return $this->json(["sucess" => "Find all users success", "data" => $users], 200);
        } catch (Exception $e) {
            return $this->json(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }
    }

    #[Route('/user/find/{id}', name: 'user_find', methods: ['GET'])]
    /**
     * Method to add user
     * @param int $id
     * @throws \Exception
     * @return JsonResponse
     */
    public function findUser(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                throw new Exception("User not found", 404);
            }

            return $this->json(["success" => "Find user success", "data" => $user], 200);
        } catch (Exception $e) {
            return $this->json(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }
    }

    /**
     * Method to add user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @throws \Exception
     * @return JsonResponse
     */
    #[Route('/user/add', name: 'user_add', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        try {
            $name = $request->request->get("name");
            $email = $request->request->get("email");

            $user = new User();
            if ($name) {
                $user->setName($name);
            }
            if ($email) {
                $user->setEmail($email);
            }

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                throw new Exception((string) $errors, 400);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json(["success" => "Add user success"], 201);

        } catch (Exception $e) {
            return $this->json(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }
    }

    /**
     * Method to update user
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @throws Exception
     * @return JsonResponse
     */
    #[Route('/user/update/{id}', name: 'user_update', methods: ['PUT'])]
    public function updateUser(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                throw new Exception("User not found", 404);
            }

            $name = $request->get("name");
            $email = $request->get("email");

            if ($name) {
                $user->setName($name);
            }
            if ($email) {
                $user->setEmail($email);
            }

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                throw new Exception((string) $errors, 400);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json(["success" => "Update user success"], 200);

        } catch (Exception $e) {
            return $this->json(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }
    }

    /**
     * Method to delete user
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @throws Exception
     * @return JsonResponse
     */
    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                throw new Exception("User not found", 404);
            }

            $entityManager->remove($user);
            $entityManager->flush();

            return $this->json(["success" => "Delete user success"], 200);
        } catch (Exception $e) {
            return $this->json(["error" => $e->getMessage()], $e->getCode() ? $e->getCode() : 500);
        }
    }
}

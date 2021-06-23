<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;



/**
 * class UserController
 * @package App\Controller
 * @Route("/users")
 */
class UserController
{
    /**
     * @Route(name="api_users_list_get", methods={"GET"})
     * @return JsonResponse
     */
    public function listOfProducts(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($userRepository->findAll(), 'json', ["groups" => "user"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_item_get", methods={"GET"})
     * @param User $user
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(User $user, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($user, 'json', ["groups" => "user"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_user_item_put", methods={"PUT"})
     * @param Request $request
     * @param User $user
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function put(
        User $user,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );

        $entityManager->flush();

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route("/{id}", name="api_user_item_delete", methods={"DELETE"})
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(
        User $user,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}

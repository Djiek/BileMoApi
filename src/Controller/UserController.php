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
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * class UserController
 * @package App\Controller
 * @Route("/users")
 * @Security(name="Bearer")
 * @OA\Tag(name="User")
 */
class UserController
{

    /**
     * @OA\Response(response=200, description="A list of users",@Model(type=User::class, groups={"userList"}))
     * @Route(name="api_users_list_get", methods={"GET"})
     * @return JsonResponse
     */
    public function listOfUsers(UserRepository $userRepository, SerializerInterface $serializer,Request $request): JsonResponse
    {
        $limit = 5;
        $page = (int)$request->query->get("page", 1);
       
         if ($page === null) {
            $page = 1;
        }

        $cache = new FilesystemAdapter();
        $user = $cache->get('listUser_' . $page, function (ItemInterface $item) use ($userRepository,$page,$limit) {
            $item->expiresAfter(30);
            $users = $userRepository->pagination($page, $limit);
            return $users;
        });

        return new JsonResponse(
            $serializer->serialize($user, 'json', ["groups" => "userList"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Response(response=200, description="Get one user with his id",@Model(type=User::class, groups={"user"}))
     * @Route("/{id}", name="api_item_get", methods={"GET"})
     * @param User $user
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item($id, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $cache = new FilesystemAdapter();
        $oneUser = $cache->get('oneUser_' . $id, function (ItemInterface $item) use ($id, $userRepository) {
            return $userRepository->find($id);
        });
        return new JsonResponse(
            $serializer->serialize($oneUser, 'json', ["groups" => "user"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Response(response=200, description="Update a user",@Model(type=User::class, groups={"user"}))
     *     @OA\Parameter(
     *         description="name of the new user",
     *         in="path",
     *         name="name",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="first name of the new user",
     *         in="path",
     *         name="first_name",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Mail of the new user",
     *         in="path",
     *         name="mail",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Adress of the new user",
     *         in="path",
     *         name="adress",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Date of birth of the new user",
     *         in="path",
     *         name="date_of_birth",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Id of the user",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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
        $cache = new FilesystemAdapter();
        $cache->delete('oneUser_' . $user->getId());
        return new JsonResponse(
            $serializer->serialize($user, 'json', ["groups" => "user"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Response(response=204, description="Delete a User",@Model(type=User::class))
     *     @OA\Parameter(
     *         description="Id of the User",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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
        $cache = new FilesystemAdapter();
        $cache->delete('oneUser_' . $user->getId());

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}

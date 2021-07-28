<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * class CustomerController
 * @package App\Controller
 * @Route("/customers")
 * @Security(name="Bearer")
 */
class CustomerController
{
    /**
    * @OA\Response(response=200, description="A list of customers",@Model(type=Customer::class, groups={"customer"}))
    * @Route(name="api_customers_list_get", methods={"GET"})
    * @return JsonResponse
    */
    public function listOfCustomers(
        CustomerRepository $customerRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $cache = new FilesystemAdapter();
        $customer = $cache->get('listCustomer', function (ItemInterface $item) use ($customerRepository) {
            return $customerRepository->findAll();
        });
        $cache->delete('listCustomer');
          return new JsonResponse(
              $serializer->serialize($customer, 'json', ["groups" => "customer"]),
              JsonResponse::HTTP_OK,
              [],
              true
          );
    }

  /**
     * @OA\Response(response=200, description="A list of users for one customer",
     * @Model(type=User::class, groups={"customerClient"}))
     *     @OA\Parameter(
     *         description="Id of the customer",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     * @Route("/{id}/users", name="api_client_customers_list_get", methods={"GET"})
     * @param  Customer $customer
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function listOfClientForOneCustomers(
        Customer $customer,
        UserRepository $userRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $cache = new FilesystemAdapter();
        $user = $cache->get(
            'customerClient_' . $customer->getId(),
            function (ItemInterface $item) use ($customer, $userRepository) {
                return $userRepository->findBy(["customer" => $customer]);
            }
        );
         return new JsonResponse(
             $serializer->serialize($user, 'json', ["groups" => "customerClient"]),
             JsonResponse::HTTP_OK,
             [],
             true
         );
    }

    /**
     * @OA\Response(response=201, description="Create a user for one customer",
     * @Model(type=User::class, groups={"userPost"}))
     *     @OA\Parameter(
     *         description="name of the new user",
     *         in="path",
     *         name="name",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="first name of the new user",
     *         in="path",
     *         name="first_name",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Mail of the new user",
     *         in="path",
     *         name="mail",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Adress of the new user",
     *         in="path",
     *         name="adress",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Date of birth of the new user",
     *         in="path",
     *         name="date_of_birth",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Id of the customer",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     * @Route("/{id}/users",name="api_user_post", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UrlGenerator $urlGenerator
     * @return JsonResponse
     * @param  Customer $customer
     */
    public function post(
        Customer $customer,
        Request $request,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        /** @var User $user*/
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $cache = new FilesystemAdapter();
        $customer->addUser($user);
        $entityManager->persist($user);
        $entityManager->flush();
        $cache->delete('customerClient_' . $customer->getId());
        return new JsonResponse(
            $serializer->serialize($user, 'json', ["groups" => "userPost"]),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_product_item_get", ["id" => $user->getId()])],
            true
        );
    }
}

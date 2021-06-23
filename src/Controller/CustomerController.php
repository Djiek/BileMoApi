<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

/**
 * class CustomerController
 * @package App\Controller
 * @Route("/customers")
 */
class CustomerController
{
    /**
     * @Route(name="api_customers_list_get", methods={"GET"})
     * @return JsonResponse
     */
    public function listOfCustomers(CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
          return new JsonResponse(
            $serializer->serialize($customerRepository->findAll(),'json',["groups"=>"customer"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );    
    } 

    /**
     * @Route("/{id}/users", name="api_client_customers_list_get", methods={"GET"})
     * @param  Customer $customer
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function listOfClientForOneCustomers(Customer $customer, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($customer->getUser() ,'json',["groups"=>"customerClient"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    
    /**
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
        $customer->addUser($user);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($user, 'json', ["groups" => "userPost"]),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_product_item_get", ["id" => $user->getId()])],
            true
        );
    }
}

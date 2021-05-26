<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
}

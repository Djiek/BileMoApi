<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

//use Symfony\Component\Routing\RequestContextAwareInterface;

/**
 * class ProductController
 * @package App\Controller
 * @Route("/products")
 */
class ProductController
{
    /**
     * @Route(name="api_products_list_get", methods={"GET"})
     * @return JsonResponse
     */
    public function listOfProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), 'json', ["groups" => "product"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_product_item_get", methods={"GET"})
     * @param  Product $product
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item(Product $product, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($product, 'json', ["groups" => "product"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route(name="api_products_post", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UrlGenerator $urlGenerator
     * @return JsonResponse
     */
    public function post(
        Request $request,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        /** @var Product $product*/
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($product, 'json', ["groups" => "product"]),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_product_item_get", ["id" => $product->getId()])],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_products_item_put", methods={"PUT"})
     * @param Request $request
     * @param Product $product
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function put(
        Product $product,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $product]
        );

        $entityManager->flush();

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route("/{id}", name="api_products_item_delete", methods={"DELETE"})
     * @param Product $product
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function delete(
        Product $product,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}

<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * class ProductController
 * @package App\Controller
 * @Route("/products")
 * @Security(name="Bearer")
 */
class ProductController
{
    /**
     * @OA\Response(response=200, description="A list of products",@Model(type=Product::class, groups={"productList"}))
     * @Route(name="api_products_list_get", methods={"GET"})
     * @return JsonResponse
     */
    public function listOfProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $cache = new FilesystemAdapter();
        $product = $cache->get('listOfProduct', function(ItemInterface $item) use ($productRepository) {
        return $productRepository->findAll();
        });
        return new JsonResponse(
            $serializer->serialize($product, 'json', ["groups" => "productList"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Response(response=200, description="Get one product with his id",@Model(type=Product::class, groups={"product"}))
     * @Route("/{id}", name="api_product_item_get", methods={"GET"})
     * @param  Product $product
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function item($id, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $cache = new FilesystemAdapter();
        $oneProduct = $cache->get('oneProduct_'.$id, function(ItemInterface $item) use ($id,$productRepository) {
            return $productRepository->find($id);
        });
        return new JsonResponse(
            $serializer->serialize($oneProduct, 'json', ["groups" => "product"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Response(response=201, description="Create a product",@Model(type=Product::class, groups={"product"}))
     *     @OA\Parameter(
     *         description="name of the new product",
     *         in="path",
     *         name="name",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Description of the new product",
     *         in="path",
     *         name="description",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Price of the new product",
     *         in="path",
     *         name="price",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Image of the new product",
     *         in="path",
     *         name="image",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
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
        $cache = new FilesystemAdapter();
        $entityManager->persist($product);
        $entityManager->flush();
        $cache->delete('listOfProduct');
        $cache->delete('oneProduct_'.$product->getId());

        return new JsonResponse(
            $serializer->serialize($product, 'json', ["groups" => "product"]),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_product_item_get", ["id" => $product->getId()])],
            true
        );
    }

    /**
     * @OA\Response(response=204, description="Update a product",@Model(type=Product::class, groups={"product"}))
     *         @OA\Parameter(
     *         description="Name of the product",
     *         in="path",
     *         name="name",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Description of the product",
     *         in="path",
     *         name="description",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Price of the product",
     *         in="path",
     *         name="price",
     *         required=false,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Image of the product",
     *         in="path",
     *         name="image",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Id of the product",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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
        $cache = new FilesystemAdapter();
        $cache->delete('listOfProduct');
        $cache->delete('oneProduct_'.$product->getId());
         return new JsonResponse(
            $serializer->serialize($product, 'json', ["groups" => "product"]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Response(response=204, description="Delete a product",@Model(type=Product::class))
     *     @OA\Parameter(
     *         description="Id of the product",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
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
        $cache = new FilesystemAdapter();
        $cache->delete('listOfProduct');
        $cache->delete('oneProduct_'.$product->getId());
        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}

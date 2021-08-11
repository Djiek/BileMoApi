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
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * class SecurityController
 * @package App\Controller
 * @Route("/login_check")
 * @OA\Tag(name="Security")
 */
class SecurityController
{
    /**
      * @OA\Response(
      *     response=200,
      *     description="Returns a connexion token",
      * )
      * @OA\Response(
      *     response=401,
      *     description="Invalid credentials",
      * )
      * @OA\RequestBody(
      *     request="connexion",
      *     description="login id",
      *     required=true,
      *     @OA\MediaType(
      *         mediaType="application/json",
      *         @OA\Schema(ref=@Model(type=Customer::class,groups={"connexion"}))
      *     )
      * )
      * @Route(name="login_check", methods={"POST"})
      */
    public function index()
    {
    }
}

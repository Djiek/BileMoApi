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
 * class SecurityController
 * @package App\Controller
 * @Route("/login_check")
 * @Security(name="Bearer")
 */
class SecurityController
{
    /**
     * @OA\Response(response=200, description="login customer")
     *      @OA\Parameter(
     *         description="mail customer",
     *         in="path",
     *         name="email",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="password customer",
     *         in="path",
     *         name="password",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     * @Route(name="login_check", methods={"GET"})
     */
    public function index()
    {} 
}
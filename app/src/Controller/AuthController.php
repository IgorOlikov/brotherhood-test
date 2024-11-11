<?php

namespace App\Controller;

use App\DTO\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\DtoToEntityMapper;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[Route('/api/v1/auth')]
class AuthController extends AbstractController
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly DtoToEntityMapper $dtoToEntityMapper,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly AuthenticationSuccessHandler $authenticationSuccessHandler
    )
    {
    }

    #[Route('/register', name: 'app_auth_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(acceptFormat: 'json', validationGroups: ['create'])]
        UserDto $userDto
    ): Response
    {
        $user = new User();

        $user = $this->dtoToEntityMapper->map($userDto, $user);

        /** @var PasswordAuthenticatedUserInterface| UserInterface | User $user */
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        /**
        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($user, 3600);

        $accessToken = $this->jwtManager->create($user);

        $refreshTokenSecureCookie = new Cookie('refresh_token', $refreshToken->getRefreshToken(), time() + 3600);

        //store refresh in database
        // store user

        //$this->authenticationSuccessHandler->handleAuthenticationSuccess()

        //$response = new JsonResponse(['status' => 'success', 'access_token' => $accessToken]);
        //$response->headers->setCookie($refreshTokenSecureCookie);
        //return $response;
        */
        return $this->authenticationSuccessHandler->handleAuthenticationSuccess($user);
    }

    #[Route('/token', name: 'app_auth_get_token', methods: ['GET'])]
    public function jwtTest(): Response
    {
        $user = $this->userRepository->find(2);
        $token = $this->jwtManager->create($user);

        return $this->json(['token' => $token]);
    }

    #[Route('/protected', methods: ['GET'])]
    public function jwtProtected(): Response
    {
        return new JsonResponse(['success']);
    }



}

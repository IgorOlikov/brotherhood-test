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


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // @TODO rabbitMQ send email

        return $this->authenticationSuccessHandler->handleAuthenticationSuccess($user);
    }


}

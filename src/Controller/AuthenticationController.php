<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AuthenticationController extends BaseController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(path: '/api/logout', name: 'logout', methods: ['POST'])]
    public function logout(UserRepository $userRepository,ManagerRegistry $doctrine){
        $userId = $this->security->getUser()->getUserIdentifier();
        $user = $userRepository->find($userId);
        $user->setTokenValidAfter(new \DateTime());

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);

        $entityManager->flush();

        return $this->respondWithSuccess([]);

    }

}
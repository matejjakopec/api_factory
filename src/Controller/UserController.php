<?php

namespace App\Controller;

use App\Controller\Export\ExportCSV;
use App\Controller\Export\ExportInterface;
use App\Controller\Export\ExportPDF;
use App\Controller\Mapper\UserMapper;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class UserController extends BaseController
{
    private $security;

    private $passwordHasher;

    public function __construct(Security $security, UserPasswordHasherInterface $passwordHasher)
    {
        $this->security = $security;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(path: 'api/register', name: 'register_user', methods: ['POST'])]
    public function registerUser(Request $request, ManagerRegistry $doctrine):Response{
        $username = $request->get('username');
        $password = $request->get('password');

        $user = new User();

        $user->setUsername($username)
            ->setContractStartDate(new \DateTime())
            ->setContractEndDate(new \DateTime())
            ->setVerified(false)
            ->setType('normal')
            ->setTokenValidAfter(new \DateTime())
            ->setPassword($this->passwordHasher->hashPassword($user, $password));

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);

        $entityManager->flush();

        return $this->respondWithSuccess(UserMapper::mapUser($user));
    }


    #[Route(path: 'api/user', name: 'current_user', methods: ['GET'])]
    public function currentUser(UserRepository $userRepository){
        $userId = $this->security->getUser()->getUserIdentifier();
        $user = $userRepository->find($userId);
        return $this->respondWithSuccess(UserMapper::mapUser($user));
    }


}
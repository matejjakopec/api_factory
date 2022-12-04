<?php

namespace App\Controller;

use App\Controller\Export\ExportCSV;
use App\Controller\Export\ExportInterface;
use App\Controller\Export\ExportPDF;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class EmailController extends BaseController
{
    public function __construct(
        private UserRepository $userRepository,
        private MailerInterface $mailer
    ){
    }


    #[Route(path: 'api/user/email/csv')]
    public function emailCSV(UserRepository $userRepository, Request $request, MailerInterface $mailer){
        $this->emailData(new ExportCSV(), $request);
        return $this->respondWithSuccess([]);
    }

    #[Route(path: 'api/user/email/pdf')]
    public function emailPDF(UserRepository $userRepository, Request $request, Environment $twig, MailerInterface $mailer){
        $this->emailData(new ExportPDF($twig), $request);
        return $this->respondWithSuccess([]);
    }

    private function emailData(ExportInterface $exporter, Request $request){
        $with = $request->get('with') ? $request->get('with') : '*';
        $to = $request->get('to') ? $request->get('to') : 'default@mail.com';
        $with = explode(',', $with);
        $users = $this->userRepository->findAll($with);
        $exporter->email($users, $this->mailer, $to);
    }
}
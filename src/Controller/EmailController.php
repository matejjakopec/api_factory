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
    #[Route(path: 'api/user/email/csv')]
    public function emailCSV(UserRepository $userRepository, Request $request, Environment $twig, MailerInterface $mailer){
        $this->emailData(new ExportCSV(), $userRepository, $request, $mailer);
        return $this->respondWithSuccess([]);
    }

    #[Route(path: 'api/user/email/pdf')]
    public function emailPDF(UserRepository $userRepository, Request $request, Environment $twig, MailerInterface $mailer){
        $this->emailData(new ExportPDF($twig), $userRepository, $request, $mailer);
        return $this->respondWithSuccess([]);
    }

    private function emailData(ExportInterface $exporter, UserRepository $userRepository, Request $request, MailerInterface $mailer){
        $with = $request->get('with') ? $request->get('with') : '*';
        $to = $request->get('to') ? $request->get('to') : 'default@mail.com';
        $with = explode(',', $with);
        $users = $userRepository->findAll($with);
        $exporter->email($users, $mailer, $to);
    }
}
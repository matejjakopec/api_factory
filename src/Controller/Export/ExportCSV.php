<?php

namespace App\Controller\Export;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ExportCSV implements ExportInterface
{

    public function export(mixed $data)
    {
        $fileContent = '';
        foreach ($data as $line){
            foreach ($line as $row){
                $fileContent .= $row . ',';
            }
            $fileContent = substr($fileContent, 0, -1);
            $fileContent .= "\r";
        }
        $response = new Response($fileContent);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'users.csv'
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    public function email(mixed $data, MailerInterface $mailer, string $to){
        $fp = fopen('users.csv', 'w+');
        foreach ($data as $line){
            fputcsv($fp, $line, ',');
        }
        $email = (new Email())
            ->from('matej@example.com')
            ->to($to)
            ->subject('users')
            ->text('Requested users')
            ->attach($fp);
        $mailer->send($email);
    }

}
<?php

namespace App\Controller\Export;

use Symfony\Component\Mailer\MailerInterface;

interface ExportInterface
{

    public function export(mixed $data);
    public function email(mixed $data, MailerInterface $mailer, string $to);

}
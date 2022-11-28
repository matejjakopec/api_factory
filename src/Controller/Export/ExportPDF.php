<?php

namespace App\Controller\Export;

use App\Controller\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ExportPDF extends BaseController implements ExportInterface
{
    private $twig;

    public function __construct(Environment $twig){
        $this->twig = $twig;
    }

    public function export(mixed $data)
    {
        $options = new Options();
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);

        $html = $this->twig->render('PDF/pdf.html.twig', array(
            'users'  => $data
        ));


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("users.pdf", [
            "Attachment" => true
        ]);
    }

    public function email(mixed $data, MailerInterface $mailer, string $to){
        $fp = fopen('users.pdf', 'w+');
        $options = new Options();
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);

        $html = $this->twig->render('PDF/pdf.html.twig', array(
            'users'  => $data
        ));


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $text = $dompdf->output();
        fwrite($fp, $text);
        $email = (new Email())
            ->from('matej@example.com')
            ->to($to)
            ->subject('users')
            ->text('Requested users')
            ->attach($fp);
        $mailer->send($email);
    }


}
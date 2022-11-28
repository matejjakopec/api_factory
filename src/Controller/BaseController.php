<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{

    public function respondWithSuccess($data){
        $response = new JsonResponse(['status' => 'success', 'data' => $data], Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function respondWithFailure($message, $statusCode){
        $response = new JsonResponse(['status' => 'failure', 'message' => $message, 'code' => $statusCode], status: $statusCode);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}
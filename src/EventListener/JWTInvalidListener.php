<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

class JWTInvalidListener
{
    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $data = ['status' => 'failure'];
        $response = new JWTAuthenticationFailureResponse('Your token is invalid, please login again to get a new one', 403);
        $response->setData($data);
        $event->setResponse($response);
    }

}
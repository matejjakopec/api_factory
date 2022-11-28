<?php

namespace App\Controller\Mapper;

use App\Entity\User;

class UserMapper
{
    public static function mapUser(User $user){
        return [
            'username' => $user->getUsername(),
            'contractStartDate' => $user->getContractStartDate(),
            'contractEndDate' => $user->getContractEndDate(),
            'verified' => $user->getVerified(),
        ];
    }

}
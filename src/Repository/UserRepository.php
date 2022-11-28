<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository
{
    public ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
    }

    public function findOneBy(array $criteria){
        return $this->doctrine->getRepository(User::class)->findOneBy($criteria);
    }

    public function find(int $id){
        return $this->doctrine->getRepository(User::class)->find($id);
    }

    public function findAll($filters = ['*']){
        $allowed = ['id', 'username', 'contract_start_date', 'contract_end_date', 'type', 'verified'];
        $sql = 'SELECT ';
        if($filters == ['*']){
            $filters = $allowed;
        }
        foreach ($filters as $filter){
            if(in_array($filter, $allowed)){
                $sql .= $filter . ', ';
            }
        }
        $sql = substr($sql, 0, -2);
        $sql .= " FROM user
                ORDER BY user.id ASC";
        $conn = $this->doctrine->getConnection();
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();

    }


}
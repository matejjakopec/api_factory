<?php

namespace App\Controller;

use App\Controller\Export\ExportCSV;
use App\Controller\Export\ExportInterface;
use App\Controller\Export\ExportPDF;
use App\Repository\UserRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;

class ExportController extends BaseController
{
    private Security $security;

    public function __construct(Security $security){
        $this->security = $security;
    }


    #[Route(path: 'api/user/export/csv')]
    public function exportCSV(UserRepository $userRepository, Request $request){
        if($this->hasReachedMaximumExports()){
            return $this->respondWithFailure('maximum exports reached', 400);
        }

        return $this->exportData(new ExportCSV(), $userRepository, $request);
    }

    #[Route(path: 'api/user/export/pdf')]
    public function exportPDF(UserRepository $userRepository, Request $request, ExportPDF $exportPDF){
        if($this->hasReachedMaximumExports()){
            return $this->respondWithFailure('maximum exports reached', 400);
        }

        return $this->exportData($exportPDF, $userRepository, $request);
    }

    private function exportData(ExportInterface $exporter, UserRepository $userRepository, Request $request){
        $with = $request->get('with') ? $request->get('with') : '*';
        $with = explode(',', $with);
        $users = $userRepository->findAll($with);

        return $exporter->export($users);
    }

    private function hasReachedMaximumExports(){
        $userId = $this->security->getUser()->getUserIdentifier();
        $cache = new FilesystemAdapter();

        $value = $cache->getItem($userId);
        if(!$value->isHit()){
            $value->set(1);
            $value->expiresAfter(30);
            $cache->save($value);

            return false;
        }

        $value->set($value->get() + 1);
        $value->expiresAfter(60);
        $cache->save($value);

        if($value->get() >= 3){
            return true;
        }

        return false;
    }

}
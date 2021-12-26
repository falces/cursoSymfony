<?php

namespace App\Controller\Api;

use App\Service\AuthorManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class AuthorController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/authors")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(
        AuthorManager $authorManager
    ): array {
        return $authorManager->getRepository()->findAll();
    }
}
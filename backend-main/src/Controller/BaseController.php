<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager ) {
        $this->entityManager = $entityManager;
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Issues;
use App\Entity\Trainee;


use App\Repository\TraineeRepository;
use App\Repository\IssuesRepo;

class GitHubController extends AbstractController
{
    #[Route('/home', name: 'git_hub')]
    public function index(EntityManagerInterface $em) : Response
    {
        $json = '
        {
            "message": "Successful request",
            "issues": [
                {
                    "id": 1871104232,
                    "title": "4. Bootsnipp",
                    "number": 5,
                    "state": "closed",
                    "labels": [
                        "week-35"
                    ],
                    "commitCount": 0
                },
                {
                    "id": 1871104207,
                    "title": "3. Layout-it",
                    "number": 4,
                    "state": "closed",
                    "labels": [
                        "week-35"
                    ],
                    "commitCount": 1
                },
                {
                    "id": 1871104185,
                    "title": "2. Grid Exercise",
                    "number": 3,
                    "state": "closed",
                    "labels": [
                        "week-35"
                    ],
                    "commitCount": 1
                },
                {
                    "id": 1871104160,
                    "title": "1. Grid Example",
                    "number": 2,
                    "state": "closed",
                    "labels": [
                        "week-35"
                    ],
                    "commitCount": 2
                },
                {
                    "id": 1871104143,
                    "title": "Introductie",
                    "number": 1,
                    "state": "closed",
                    "labels": [
                        "week-35"
                    ],
                    "commitCount": 1
                }
            ],
            "name": "Stan Hillen",
            "traineeID": 48620909,
            "repoID": "educom-bootstrap-1693295632",
            "repoName": "educom-bootstrap-1693295632",
            "avatar_url": "https://avatars.githubusercontent.com/u/48620909?v=4"
        }';
        dd($json);
    }

    private function convertStringToDate(string $datumString): ?\DateTimeInterface {
        // Splits de datumstring op het "-"-teken
        $parts = explode('-', $datumString);
    
        // Controleer of het formaat geldig is (bijv. "week-xx")
        if (count($parts) === 2 && strtolower($parts[0]) === 'week') {
            // Zet het tweede deel om naar een integer als het weeknummer
            $weekNumber = (int)$parts[1];
    
            // Bereken de datum op basis van het weeknummer en huidig jaar
            $date = new \DateTime();
            $date->setISODate($date->format('Y'), $weekNumber);
            
            // Retourneer het DateTime-object
            return $date;
        }
    
        // Retourneer null als het formaat niet overeenkomt
        return null;
    }

    private function convertStringToStartDate(array $labels): ?\DateTimeInterface {
        // Splits de datumstring op het "-"-teken
        $parts = explode('-', $labels[0]);
    
        // Controleer of het formaat geldig is (bijv. "week-xx")
        if (count($parts) === 2 && strtolower($parts[0]) === 'week') {
            // Zet het tweede deel om naar een integer als het weeknummer
            $weekNumber = (int)$parts[1];
    
            // Bereken de datum op basis van het weeknummer en huidig jaar
            $date = new \DateTime();
            $date->setISODate($date->format('Y'), $weekNumber);
            
            // Retourneer het DateTime-object
            return $date;
        }
    
        // Retourneer null als het formaat niet overeenkomt
        return null;
    }

    public function convertStringToEndDate(array $labels): ?\DateTimeInterface {
        // Splits de datumstring op het "-"-teken
        $parts = explode('-', end($labels));
    
        // Controleer of het formaat geldig is (bijv. "week-xx")
        if (count($parts) === 2 && strtolower($parts[0]) === 'week') {
            // Zet het tweede deel om naar een integer als het weeknummer
            $weekNumber = (int)$parts[1];
    
            // Bereken de datum op basis van het weeknummer en huidig jaar
            $date = new \DateTime();
            $date->setISODate($date->format('Y'), $weekNumber);
            $date = $date->modify( '+ 4 days' );
            // Retourneer het DateTime-object
            return $date;
        }
    
        // Retourneer null als het formaat niet overeenkomt
        return null;
    }
}



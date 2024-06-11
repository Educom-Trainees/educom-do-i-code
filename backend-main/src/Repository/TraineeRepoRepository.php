<?php

namespace App\Repository;

use App\Entity\Trainee;
use App\Entity\Repo;
use App\Entity\TraineeRepo;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\RepoRepository;
use App\Repository\TraineeRepository;

/**
 * @extends ServiceEntityRepository<TraineeRepo>
 *
 * @method TraineeRepo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TraineeRepo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TraineeRepo[]    findAll()
 * @method TraineeRepo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraineeRepoRepository extends ServiceEntityRepository
{
    private $TraineeRepository;
    private $RepoRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TraineeRepo::class);
        $this->TraineeRepository = $this->_em->getRepository(Trainee::class);
        $this->RepoRepository = $this->_em->getRepository(Repo::class);
    }

    public function getAllTraineeRepos() {
        return($this->findAll());
    }
/*
    public function saveTraineeRepos($params) {

        if(isset($params["id"]) && $params["id"] != "") {
            $TraineeRepos = $this->find($params["id"]);
        } else {
            $TraineeRepos = new TraineeRepo();
        }
        
        $TraineeRepos->setIdTrainee($params["id_trainee"]);
        $TraineeRepos->setIdRepo($params["id_repo"]);
        $TraineeRepos->setStartDate($params["start_date"]);
        $TraineeRepos->setEndDate($params["end_date"]);

        $this->_em->persist($TraineeRepos);
        $this->_em->flush();

        return($TraineeRepos);
    }
    */

    public function saveTraineeRepo($trainee, $repo) {

        $traineeRepo = $this->findOneBy(['repo' => $repo, 'trainee' => $trainee]);

        if (!$traineeRepo) {
            $traineeRepo = new TraineeRepo();
            $traineeRepo->setTrainee($trainee);
            $traineeRepo->setRepo($repo);
        } 

        $this->_em->persist($traineeRepo);
        $this->_em->flush();

        return($traineeRepo);
    }

    // private function fetchTrainee($traineeID) {
    //     if(is_null($traineeID)) return(null);
    //     return($this->TraineeRepository->fetchTrainee($traineeID));
    // }

    // private function fetchRepo($repoID) {
    //     if(is_null($repoID)) return(null);
    //     return($this->RepoRepository->fetchRepo($repoID));
    // }

    private function convertStringToEndDate(array $labels): ?\DateTimeInterface {
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


//    /**
//     * @return TraineeRepo[] Returns an array of TraineeRepo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TraineeRepo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

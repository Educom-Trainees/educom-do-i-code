<?php

namespace App\Repository;

use App\Entity\Repo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Repo>
 *
 * @method Repo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repo[]    findAll()
 * @method Repo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repo::class);
    }

    public function getAllRepos() {
        return($this->findAll());
    }

    public function saveRepo($repoName) {
        $nameArray = explode("-", $repoName);
        $repoName = strtolower($nameArray[1]);
        
        $repo = $this->findOneBy(['name' => $repoName]);

        if(!$repo) {
            $repo = new Repo();
            $repo->setName($repoName);
            $this->_em->persist($repo);
            $this->_em->flush();
        }

        return($repo);
            
    }

    public function fetchRepo($repoID) {
        return($this->find($repoID));
    }
//    /**
//     * @return Repo[] Returns an array of Repo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Repo
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Commits;
use App\Entity\Issues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commits>
 *
 * @method Commits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commits[]    findAll()
 * @method Commits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commits::class);
    }
    public function saveCommits(array $commits)
    {
        $em = $this->getEntityManager();
        foreach ($commits as $commitData) {
            $commit = new Commits;
            $commit->setMessage($commitData['message']);
            $commit->setStart(new \DateTime($commitData['date']));
            if ($commitData['issue_id'] !== null) {
                $issue = $em->getReference(Issues::class, $commitData['issue_id']);
                $commit->setIssueId($issue);
            }
            $em->persist($commit);
        }
        $em->flush();
    }

//    /**
//     * @return Commits[] Returns an array of Commits objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commits
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

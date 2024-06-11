<?php

namespace App\Repository;

use App\Entity\Trainee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trainee>
 *
 * @method Trainee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trainee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trainee[]    findAll()
 * @method Trainee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraineeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trainee::class);
    }

    public function getAllTrainees() {
        return($this->findAll());
    }

    public function saveTrainee($name, $traineeID, $avatar_url) {

        $trainee = $this->findOneBy(['traineeID' => $traineeID]);

        if(!$trainee) {
            $trainee = new Trainee();
            $trainee->setName($name);
            $trainee->setTraineeID($traineeID);
            $trainee->setAvatarURL($avatar_url);
        } else {
            $trainee->setName($name);
            $trainee->setAvatarURL($avatar_url);
        }

        $this->_em->persist($trainee);
        $this->_em->flush();

        return($trainee);      
    }

    public function fetchTrainee($traineeID) {
        return($this->find($traineeID));
    }



//    /**
//     * @return Trainee[] Returns an array of Trainee objects
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

//    public function findOneBySomeField($value): ?Trainee
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Issues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Psr\Log\LoggerInterface;


/**
 * @extends ServiceEntityRepository<Issues>
 *
 * @method Issues|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issues|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issues[]    findAll()
 * @method Issues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssuesRepository extends ServiceEntityRepository
{
    private $logger;
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Issues::class);

        $this->logger = $logger;
    }

    public function getAllIssues()
    {
        return ($this->findAll());
    }

    public function SaveIssues($traineeRepo, $issues)
    {   
        $issueEntities = [];
        // gathering all weeks for this repo to check for possible change in year
        $repoWeekNumbers = [];
        foreach($issues as $issue)
        {
            $labels = $issue['labels'];

            $issueWeeks = $this->labelsToWeekNumbers($labels);
            $repoWeekNumbers = array_unique(array_merge($repoWeekNumbers, $issueWeeks));
        }

//      if week 52 or 51 is detected we assume a possible change in year in case other weeks are early in the year
//      if you want to change when it's decided to flag for a possible year change you can edit the if statement here
        if(max($repoWeekNumbers) >= 51)
        {
            $yearChange = true;
        } else 
        {
            $yearChange = false;
        }

        foreach($issues as $issueData)
        {
            $issue = $this->findOneBy(['trainee_repo' => $traineeRepo, 'issueNumber' => $issueData['number']]);

            // Check if issue already exists
            if (!$issue) 
            {
                // Create a new issue with all the new information
                $issue = new Issues;
                $issue->setTraineeRepo($traineeRepo);
                $issue->setDescription($issueData['title']);
                $issue->setIssueNumber($issueData['number']);
                $issue->setNumberOfCommits($issueData['commitCount']);

                $date = new \DateTime($issueData['created_at']);

                $weekNumbers = $this->labelsToWeekNumbers($issueData['labels']);
        
                $startDate = $this->convertWeekToStartDate($weekNumbers, $yearChange, $date->format('Y'));
                $issue->setStartDate($startDate);
        
                if ($issueData['state'] == 'closed') 
                {
                    $closeDate = $this->convertStringToEndDate($issueData['closed_at']);
                    $endDate = $this->convertWeekToEndDate($weekNumbers, $yearChange, $date->format('Y'));

                    // if enddate is before startdate we assume the task to be finished at the time the task starts
                    // this way we deal with times where trainees finish an issue before the planned starttime
                    // if closeDate is after startDate we compare endDate with closeDate to see which is earlier
                    // then we choose the earliest so if the trainee forgets to close the issue the database doesn't assume
                    // that they took 2 months to complete
                    if($closeDate < $startDate) 
                    {
                        $issue->setEndDate($startDate);
                    } else 
                    {
                        $issue->setEndDate($closeDate < $endDate ? $closeDate: $endDate);
                    }
                } else 
                {
                    $issue->setEndDate(null);
                }
            // if the issue does exist only update the values
            // we only update commitcount, title and the enddate in case they have changed since creation
            // the other fields don't change
            } else 
            {
                $issue->setNumberOfCommits($issueData['commitCount']);
                $issue->setDescription($issueData['title']);

                if ($issueData['state'] == 'closed') 
                {
                    $weekNumbers = $this->labelsToWeekNumbers($issueData['labels']);
                    $startDate = $issue->getStartDate();

                    $closeDate = $this->convertStringToEndDate($issueData['closed_at']);
                    $endDate = $this->convertWeekToEndDate($weekNumbers, $yearChange, $startDate->format('Y'));
                    
                    // same as above
                    if($closeDate < $startDate) 
                    {
                        $issue->setEndDate($startDate);
                    } else 
                    {
                        $issue->setEndDate($closeDate < $endDate ? $closeDate: $endDate);
                    }
                }
            }
        
            $this->_em->persist($issue);
            $this->_em->flush();

            array_push($issueEntities, $issue);
        }

        return $issueEntities;
            
    }

    private function labelsToWeekNumbers(array $labels): ?array
    {
        $weekNumbers = [];

        foreach($labels as $label)
        {

            if(!(str_contains(strtolower($label) , "week-"))) { continue; }
            $labelName = $label;
            $parts = explode('-', $labelName);
            array_push($weekNumbers, (int)$parts[1]);
        }
        return $weekNumbers;
    }


    private function convertWeekToStartDate(array $weekNumbers, bool $yearChange, int $year): ?\DateTimeInterface
    {
        sort($weekNumbers);
        $startDate = null;

        foreach ($weekNumbers as $weekNumber) 
        {
            $date = new \DateTime();
            if($weekNumber <= 5 && $yearChange) // if yearchange detected and weeknumber <= 10 we add a year
            {
                $date->setISODate($year + 1 , $weekNumber);   
            } else 
            {
                $date->setISODate($year, $weekNumber);
            }

            // compare dates to take the earliest startdate in the weeknumber list
            if ($startDate === null || $date < $startDate) {
                $startDate = $date;
            }
        }
        return $startDate;
    }

    private function convertWeekToEndDate(array $weekNumbers, bool $yearChange, int $year): ?\DateTimeInterface
    {
        sort($weekNumbers);
        $endDate = null;

        foreach ($weekNumbers as $weekNumber) 
        {
            $date = new \DateTime();
            if($weekNumber <= 5 && $yearChange) // if yearchange detected and weeknumber <= 10 we add a year
            {
                $date->setISODate($year + 1 , $weekNumber);  
            } else {
                $date->setISODate($year, $weekNumber);
            }

            $date = $date->modify('+4 days');

            // compare dates to take the earliest startdate in the weeknumber list
            if ($endDate === null || $date > $endDate) 
            {
                $endDate = $date;
            }
        }
        return $endDate;
    }

    private function convertStringToEndDate(string $closed_at): ?\DateTimeInterface
    {
        if ($closed_at == null) { return $closed_at; }
        
        return new \DateTime($closed_at);
    }

    
    //    /**
    //     * @return Issues[] Returns an array of Issues objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Issues
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TraineeRepository;
use App\Repository\IssuesRepository;
use App\Repository\RepoRepository;
use App\Repository\TraineeRepoRepository;
use App\Entity\TraineeRepo;
use App\Entity\Issues;
use App\Repository\CommitsRepository;
// need for testing
use Symfony\Component\PropertyAccess\PropertyAccessor;


/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={
 *         "put"={
 *             "method"="PUT",
 *             "path"="/api/put",
 *             "controller"=ApiController::class,
 *             "deserialize"=false,
 *         },
 *     },
 * )
 */
class ApiController extends BaseController
{
    /**
     * @Route("/api/put", methods={"PUT})
     */
    public function putRequest(
        Request $request, 
        TraineeRepository $traineeRepository, 
        RepoRepository $repoRepository, 
        IssuesRepository $issuesRepository, 
        CommitsRepository $commitsRepository,
        TraineeRepoRepository $traineeRepoRepository): JsonResponse
    {
        // password that we use to verify the api call
        $password = $_ENV['BACKEND_PASSWORD'];
        $jsonData = json_decode($request->getContent(), true);

        // check if password is included: 
        if($jsonData['password'] !== $password)
        {
            return new JsonResponse(['Acces Denied' => 'Incorrect authorization'], 400);
        }
        
        $issuesData = $jsonData['issues'];
        $repoData = $jsonData['repository'];
        $commitsData = $jsonData['commits'];
        $owner = $repoData['owner'];
        $repoName = $repoData['name'] ?? null;

        if(!str_contains($repoName, "educom"))
        {
            return new JsonResponse(['error' => 'Repo is not an educom repo'], 400);
        }

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: my-app/1.0\r\n" 
            ]
        ];
        
        $context = stream_context_create($opts);

        $accountJson = file_get_contents($owner['url'], false, $context);
        $accountData = json_decode($accountJson, true);

        $name = $owner['login'];
        if ( $accountData['name'] != null){ $name = $accountData['name']; }

        $commitIssueNumbers = [];
        $commitCounts = [];

        $commits = [];
        foreach($commitsData as $commitData){
            $commit = $commitData['commit'];
            $message = $commit['message'];
            $datetime = $commit["committer"]["date"];
            if($message == "Initial commit"){ continue; }
            $issueNumber = 0;

            $pattern = '/#(\d+)/';
            if(preg_match($pattern, $message, $matches)) {
                $issueNumber = intval($matches[1]);
            } 

            array_push($commitIssueNumbers, $issueNumber);
            $commits[] = [
                'message' => $message,
                'date' => $datetime,
                'issueNumber' => $issueNumber,
            ];
        }

        foreach($commitIssueNumbers as $issueNumber){
            if(isset($commitCounts[$issueNumber])) {
                $commitCounts[$issueNumber]++;
            } else {
                $commitCounts[$issueNumber] = 1;
            }
        }

        $issues = [];
        foreach ($issuesData as $issueData) {
            $issueID = $issueData['id'];
            $title = $issueData['title'];
            $number = $issueData['number'];
            $state = $issueData['state'];
            $labelsData = $issueData['labels'];
            $created_at = $issueData['created_at'];
            $closed_at = $issueData['closed_at'];
            $labels = [];

            foreach ($labelsData as $label) {
                array_push($labels, $label['name']);
            }

            $commitCount = $commitCounts[$number] ?? 0;
            
            $issuePruned = [
                'id'=>$issueID,
                'title'=>$title,
                'number'=>$number,
                'state'=>$state,
                'labels'=>$labels,
                'commitCount'=>$commitCount,
                'created_at'=> $created_at,
                'closed_at' => $closed_at
            ];
            array_push($issues, $issuePruned);
        }

        $repoID = $repoData['id'] ?? null;
        $traineeID = $owner['id'] ?? null;
        $avatar_url = $owner['avatar_url'] ?? null;

        if(!$repoName || !$repoID || !$traineeID || !$name)
        {
            return new JsonResponse(['error' => 'Necessary variables could not be assigned'], 400);
        }

        $trainee = $traineeRepository->saveTrainee($name, $traineeID, $avatar_url);
        $repo = $repoRepository->saveRepo($repoName);
        $traineeRepo = $traineeRepoRepository->saveTraineeRepo($trainee, $repo);
        $issues = $issuesRepository->SaveIssues($traineeRepo, $issues);

        // Fetch the mapping of issue numbers to issue IDs
        $issueNumberToIdMap = $issuesRepository->fetchIssueNumberToIdMap($repoID);

        // Prepare the commits with the correct issue IDs
        foreach ($commits as &$commit) {
            $issueNumber = $commit['issueNumber'];
            if (isset($issueNumberToIdMap[$issueNumber])) {
                $commit['issue_id'] = $issueNumberToIdMap[$issueNumber];
            } else {
                $commit['issue_id'] = null; // or handle this case as needed
            }
            unset($commit['issueNumber']); // remove issueNumber if no longer needed
        }

        // Save the commits
        $commitsRepository->saveCommits($commits);
        
        return new JsonResponse(['message' => 'Successful request'], 204);
    }
}


?>
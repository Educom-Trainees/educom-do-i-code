<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TraineeRepository;
use App\Repository\IssuesRepository;
use App\Repository\RepoRepository;
use App\Repository\TraineeRepoRepository;

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
        TraineeRepoRepository $traineeRepoRepository): JsonResponse
    {
        // password that we use to verify the api call
        $password = "z3Q#!A4ZCqsids";
        $jsonData = json_decode($request->getContent(), true);

        // check if password is included: 
        if($jsonData['password'] !== $password)
        {
            return new JsonResponse(['Acces Denied' => 'Incorrect authorization'], 400);
        }
        // decode the input json to retrieve necessary information
        // retrieve the issues, repository and owner entities from the json
        $issuesData = $jsonData['issues'];
        $repoData = $jsonData['repository'];
        $commitsData = $jsonData['commits'];

        $owner = $repoData['owner'];


        // filter out non-educom repos from being added to the database
        $repoName = $repoData['name'] ?? null;

        if(!str_contains($repoName, "educom"))
        {
            return new JsonResponse(['error' => 'Repo is not an educom repo'], 400);
        }

        // Currently no token because only the name is used which is public 
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: my-app/1.0\r\n" 
            ]
        ];
        
        $context = stream_context_create($opts);

        $accountJson = file_get_contents($owner['url'], false, $context);
        $accountData = json_decode($accountJson, true);

        // name of the logged is user is their actual name if it's entered in their account information else it's just their username
        // since if no name is given in the account data the name variable = null 
        $name = $owner['login'];

        if ( $accountData['name'] != null){ $name = $accountData['name']; }
        
        // make an array of the issuenumber for each commit (0 means there was no indicated issue number) 
        $commitIssueNumbers = [];

        foreach($commitsData as $commitData){
            $commit = $commitData['commit'];
            $message = $commit['message'];
            if($message == "Initial commit"){ continue; } // skips rene's automated initial commit message
            $issueNumber = 0;

            $pattern = '/#(\d+)/';

            if(preg_match($pattern, $message, $matches)) {
                $issueNumber = intval($matches[1]);
            } 

            array_push($commitIssueNumbers, $issueNumber);
        }

        // check if this number is already in commitCounts if it is add 1 else make it 1 (count the commits per issue)
        // commitcounts is an array of ["issuenumber" => amount of commits, ...]
        foreach($commitIssueNumbers as $issueNumber){
            if(isset($commitCounts[$issueNumber])) {
                $commitCounts[$issueNumber]++;
            } else {
                $commitCounts[$issueNumber] = 1;
            }
        }

        $issues = [];
        //using for loop to extract each single issue's data
        foreach ($issuesData as $issueData) {
            $issueID = $issueData['id'];
            $title = $issueData['title'];
            $number = $issueData['number'];
            $state = $issueData['state'];
            $labelsData = $issueData['labels'];
            $created_at = $issueData['created_at'];
            $closed_at = $issueData['closed_at'];
            $labels = [];
            //another loop to take all the labels separately 
            foreach ($labelsData as $label) {
                array_push($labels, $label['name']);
            }
            // look in commitcount if this issue has any commits
            if (isset($commitCounts[$number])) {
                $commitCount = $commitCounts[$number];
            } else {
                $commitCount = 0;
            }
            //new array for each separate issue with they representative data
            $issuePruned = array(
                'id'=>$issueID,
                'title'=>$title,
                'number'=>$number,
                'state'=>$state,
                'labels'=>$labels,
                'commitCount'=>$commitCount,
                'created_at'=> $created_at,
                'closed_at' => $closed_at
            );
            //add this issue to the main array
            array_push($issues, $issuePruned);
        };
        // each entry in the array issues is a separate issue with only the necessary data
        // including: title, number, state and an array of all the labels for that issue

        
        $repoID = $repoData['id'] ?? null;
        $traineeID = $owner['id'] ?? null;
        $avatar_url = $owner['avatar_url'] ?? null;

        // check if these variables are assigned and return error in case they aren't
        if(!$repoName || !$repoID || !$traineeID || !$name)
        {
            return new JsonResponse(['error' => 'Necessary variables could not be assigned'], 400);
        }

        $trainee = $traineeRepository->saveTrainee($name, $traineeID, $avatar_url);
        $repo = $repoRepository->saveRepo($repoName);
        $traineeRepo = $traineeRepoRepository->saveTraineeRepo($trainee, $repo);
        $issues = $issuesRepository->SaveIssues($traineeRepo, $issues);
        

        // remember to add status 204 when replying with a succesfull request 
        // 204 does make the response empty so if frontend needs confirmation message just use the default
        return new JsonResponse([
            'message' => 'Successful request'
            ], 204);        
    }
}

?>
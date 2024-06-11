import { getServerSession } from "next-auth/next"
import { authOptions } from "../../../api/auth/[...nextauth]/authoptions"

import Details from "./details"
import Chart from "./chart"

async function fetchRepository(owner: string, repo: string) {
  const session = await getServerSession(authOptions);

  // https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#get-a-repository
  const response = await fetch("https://api.github.com/repos/" + owner + "/" + repo, {
    headers: {
      'Authorization': `Bearer ${session.access_token}`,
      'X-GitHub-Api-Version': '2022-11-28',
      'Accept': 'application/vnd.github+json'
    },
  });

  if (!response.ok) {
    console.error('Error fetching repository from GitHub.');
  }

  const data = await response.json();
  return data;
}

async function fetchIssues(owner: string, repo: string) {
  const session = await getServerSession(authOptions);

  let nextRequest = "https://api.github.com/repos/" + owner + "/" + repo + "/issues?state=all&per_page=100&direction=asc";
  let result: any[] = [];

  while (nextRequest != null) {
    // https://docs.github.com/en/rest/issues/issues?apiVersion=2022-11-28#list-repository-issues
    const response = await fetch(nextRequest, {
      headers: {
        'Authorization': `Bearer ${session.access_token}`,
        'X-GitHub-Api-Version': '2022-11-28',
        'Accept': 'application/vnd.github+json'
      },
    });

    if (!response.ok) {
      console.error('Error fetching issues from GitHub.');
    }

    // add data to result
    const data = await response.json();
    result = result.concat(data);

    // determine next fetch
    const linkHeader = response.headers.get('Link');
    if (linkHeader?.includes('>; rel="next"')) {
      nextRequest = linkHeader.split('>; rel="next"')[0].substring(1);
    } else {
      break
    }
  }

  return result;
}

async function fetchCommits(owner: string, repo: string) {
  const session = await getServerSession(authOptions);

  let nextRequest = "https://api.github.com/repos/" + owner + "/" + repo + "/commits?per_page=100";
  let result: any[] = [];

  while (nextRequest != null) {
    // https://docs.github.com/en/rest/commits/commits?apiVersion=2022-11-28#list-commits
    const response = await fetch(nextRequest, {
      headers: {
        'Authorization': `Bearer ${session.access_token}`,
        'X-GitHub-Api-Version': '2022-11-28',
        'Accept': 'application/vnd.github+json'
      },
    });

    if (!response.ok) {
      console.error('Error fetching issues from GitHub.');
    }

    // add data to result
    const data = await response.json();
    result = result.concat(data);

    // determine next fetch
    const linkHeader = response.headers.get('Link');
    if (linkHeader?.includes('>; rel="next"')) {
      nextRequest = linkHeader.split('>; rel="next"')[0].substring(1);
    } else {
      break
    }
  }

  return result;
}

export default async function Repository({ params }: { params: { owner: string, repo: string } }) {
  const repositoryData = await fetchRepository(params.owner, params.repo);
  const issuesData = await fetchIssues(params.owner, params.repo);

  // backend endpoint request
  if (process.env.BACKEND_ENDPOINT != undefined) {
    const commitsData = await fetchCommits(params.owner, params.repo);
    var headers = new Headers();
    headers.append("Content-Type", "application/json");
  
    var requestOptions = {
      method: 'PUT',
      headers: headers,
      body: JSON.stringify({ "repository": repositoryData, "issues": issuesData, "commits": commitsData, "password": process.env.BACKEND_PASSWORD })
    };
  
    fetch(process.env.BACKEND_ENDPOINT, requestOptions)
      .then(response => response.text())
      .then(result => console.log(result))
      .catch(error => console.log('Error sending request to backend endpoint:', error));
  }

  return (
    <>
      {issuesData.length >= 100 &&
        <div role="alert" className="alert alert-warning mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" className="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
          <span>Alleen de meest recent aangemaakte issues zijn verwerkt geworden.</span>
        </div>
      }
      <h1>{params.owner + "/" + params.repo}</h1>
      <Details data={repositoryData} />
      <div className="h-4" />
      <Chart data={issuesData} />
    </>
  )
}
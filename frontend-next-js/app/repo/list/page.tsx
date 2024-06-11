import { getServerSession } from "next-auth/next"
import { authOptions } from "../../api/auth/[...nextauth]/authoptions"
import List from "./list"

async function fetchRepositories() {
  const session = await getServerSession(authOptions);

  // https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#list-repositories-for-the-authenticated-user
  const response = await fetch("https://api.github.com/user/repos?sort=updated&per_page=100", {
    headers: {
      'Authorization': `Bearer ${session.access_token}`,
      'X-GitHub-Api-Version': '2022-11-28',
      'Accept': 'application/vnd.github+json'
    },
  });

  if (!response.ok) {
    console.error('Error fetching repository list from GitHub.');
  }

  const data = await response.json();
  return data;
}

export default async function Repository() {
  const data = await fetchRepositories();
  return (
    <>
      {data.length >= 100 &&
      <div role="alert" className="alert alert-warning mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" className="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span>Alleen de meest recent geüpdatete repositories worden hier weergeven. Als jouw gewenste repository niet in de onderstaande lijst staat, push een commit of update een issue en probeer het opnieuw.</span>
      </div>
      }
      <h1>Repositorylijst</h1>
      <List data={data}/>
    </>
  )
}
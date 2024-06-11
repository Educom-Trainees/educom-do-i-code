import Link from "next/link";

export default function Details({data}: {data: any}) {
  
  return (
    <>
      <ul>
        <li>Repository: {data["name"]}</li>
        <li>Eigenaar: {data["owner"]["login"]}</li>
        <li>Zichtbaarheid: {data["private"] ? "Privé" : "Openbaar"}</li>
        <li>Geopende Issues: {data["open_issues_count"]}</li>
      </ul>
      {/* <Link className="btn btn-primary" href={"https://github.com/" + data["full_name"]}>Open in GitHub</Link> */}
    </>
  )
}
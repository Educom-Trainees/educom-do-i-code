import Navigation from "../../navigation"
import Content from "../../content"

export default function RepoLayout({ params, children }: { params: { owner: String, repo: String }, children: React.ReactNode }) {
    return (
        <>
            <Navigation repoFullName={ params.owner + "/" + params.repo} currentRoute={ "/repo/" + params.owner + "/" + params.repo}/>
            <Content>
                {children}
            </Content>
        </>
    )
}
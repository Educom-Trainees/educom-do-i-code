import Navigation from "../navigation"
import Content from "../content"

export default function RepoListLayout({ children }: { children: React.ReactNode }) {
    return (
        <>
            <Navigation currentRoute="/repo/list"/>
            <Content>
                {children}
            </Content>
        </>
    )
}
import NextAuth from "next-auth";
import { authOptions } from "./authoptions";

// export const authOptions = {
//     secret: process.env.NEXTAUTH_SECRET,
//     providers: [
//         GithubProvider({
//             clientId: process.env.GITHUB_CLIENT_ID!,
//             clientSecret: process.env.GITHUB_CLIENT_SECRET!,
//             authorization: {
//                 params: { scope: "read:user user:email repo" }
//             }
//         })
//     ],
//     callbacks: {
//         async signIn({ account }: { account: any }) {

//             // https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#list-repositories-for-the-authenticated-user
//             const response = await fetch("https://api.github.com/user/repos", {
//                 headers: {
//                     'Authorization': `Bearer ${account.access_token}`,
//                     'X-GitHub-Api-Version': '2022-11-28',
//                     'Accept': 'application/vnd.github+json'
//                 },
//             });

//             if (!response.ok) {
//                 return false
//             }

//             const data = await response.json();

//             // console.log(data);

//             return true;

//         },
//         async jwt({ token, account }: { token: any, account: any }) {
//             // persist the OAuth access_token to the token right after signin
//             if (account) {
//                 token = Object.assign({}, token, { access_token: account.access_token });
//                 token.accessToken = account.accessToken;
//             }
//             return token
//         },
//         async session({ session, token, user }: { session: any, token: any, user: any }) {
//             if (session) {
//                 session = Object.assign({}, session, { access_token: token.access_token })
//             }
//             return session
//         }
//     }
// }

const handler = NextAuth(authOptions);

export { handler as GET, handler as POST }
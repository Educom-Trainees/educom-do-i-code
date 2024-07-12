# ERD Do-I-Code
```mermaid

erDiagram
    Trainee {
        string id    PK
        string name  FK
        int    trainee_id
        string avatar_url 
    }

    Repo {
        string id    PK
        string name  FK
    }

    Trainee_Repo {
        int    id PK
        int    trainee FK
        int    repo FK
        int    github_repo_id
        date   start_date
        date   end_date
    }
    Trainee }o--|| Trainee_Repo : References 
    Repo }o--|| Trainee_Repo : References 
    Issues {
        int    id              PK
        int    trainee_repo_id FK
        string description
        int    issue_number  
    }    
    Trainee_Repo ||--o{ Issues : Has
    Trainee_Repo ||--o{ Commits: IsPartOf

    Commits {
        int id PK
        int trainee_repo_id FK
        datetime start
        string message
    }

    Commit_Issues {
        int id PK
        int issue_id FK
        int commit_id FK
    }
    Issues ||--o{ Commit_Issues : Has
    Commits ||--o{ Commit_Issues: Has

%% Some commits do not belong to an issue
%% But commits always belong to a repo
```

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

    Commits {
        int id PK
        int issue_id FK
        datetime start
        string message
    }
    Issues ||--o{ Commits : Has
```

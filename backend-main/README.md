# The backoffice for the do-I-code application

Produces an api for the do-I-code application.
It uses an [Symfony API platform](https://e-learning.educom.nu/elaborations/Symfony-5.4/api-platform/) to expose an endpoint for the do-I-code front-end application. this builds on the common Symfony concepts like [Entities](https://e-learning.educom.nu/elaborations/Symfony-5.4/entities/) and [Repositories](https://e-learning.educom.nu/elaborations/Symfony-5.4/repositories/)

# Setup
1) Install [composer](https://getcomposer.org/doc/00-intro.md#installation-windows) if you don't already have Composer
2) Open an terminal and navigate to the `backend-main` folder 
```bash 
cd ./backend-main
```
3) install all dependencies 
```bash
composer install
```
4) Download [symfony-cli](https://symfony.com/download)

5) Create a database using the two commands below
> [!WARNING]
> If your root user has a password, place the password between the `:` and the `@` in the following line in `.env` file before executing the commands
> `DATABASE_URL="mysql://root:@127.0.0.1:3306/do_i_code_db?serverVersion=mariadb-10.4.28&charset=utf8mb4"`
> 
> Will not work with passwords containing URL escape characters, such as `/?:;@#=&`. In this case, you can use [URL encoded](https://www.w3schools.com/tags/ref_urlencode.ASP) passwords (i.e. change `?` to `%3F`). In this case, you may need to change the line in `config/packages/doctrine.yaml` from \
>  `url: '%env(resolve:DATABASE_URL)%'` to `url: '%env(DATABASE_URL)%'` \
> Alternatively, create a different user with a password without escape characters (and change `root` to the new user's name).

```bash 
symfony console doctrine:database:create
symfony console doctrine:schema:create
```

# Run the application
1) Run `symfony serve`
2) The API of the applicatie runs on http://localhost:8000
3) Test the application by going to http://localhost:8000/home and verify a json response is given

## Routes:
   **GET** /home

   **PUT** /api/put
```js
   body: {
     "password": "z3Q#!A4ZCqsids",
     "repository": {
            "id": "2352346", // id of github
            "owner": {
                "login": "trainee name",
                "url": "http://github.com/trainee_name"
                // ...
            },
            "name": "educom-..." // Must start with educom-
            // ...

     },
     "issues": [
        {
            "id": "1434985", // Id of github issue
            "title":"The issue title",
            "number": "14",
            "state": "active", // active or closed
            "labels": [ 
                { "name": "week-34" /* ... */ },
                { "name": "bug" /* ....... */ }, 
                { "name": "invalid" /* ... */ } 
            ],
            "created_at": "2024-05-26T13:34:11Z",
            "closed_at": "2024-05-29T12:11:33Z" // or "null" if still active
        } 
        // ...
     ],
     "commits": [
        {
            "commit": {
                "message": "Fixed issue #14 ..."
                // ... 
            }
        }
     ]
   }
```    
* The contents of field `repository` is equal to the result of the [`/repos/{owner}/{repo}` GitHub REST API endpoint](https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#get-a-repository), 
* The contents of `issues` is equal to the result of the [`/repos/{owner}/{repo}/issues` GitHub REST API endpoint](https://docs.github.com/en/rest/issues/issues?apiVersion=2022-11-28#list-repository-issues) 
* The contents of `commits` is equal to the result of the [`/repos/{owner}/{repo}/commits` GitHub REST API endpoint](https://docs.github.com/en/rest/commits/commits?apiVersion=2022-11-28#list-commits).

## Data structure
The data structure of the application is described in the [ERD](./ERD.md)
The application structure diagram is described in the [ASD](./ASD.graphml.png)

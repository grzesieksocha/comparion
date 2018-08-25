# Comparion 
Comparion provides an easy way to compare GitHub repositories.

### Guide

To run the server
```
composer install
php bin/console server:run

```

**WARNING**

For some repositories counting the number of open / closed pull requests requires more than 60 requests to the Github API.

In this case you must be an authenticated user. To do this change the below variables in the .env file

```
GITHUB_AUTH_ENABLED=true
GITHUB_USERNAME=your_username
GITHUB_SECRET=your_token_from_github
```

### Available paths
{identifier} <- url encoded full name (owner/repository_name) or a link to a repository 

#### Repository
/api/repository/{identifier} - provides a basic data about a repository
```
http://localhost:8000/api/repository/https%3A%2F%2Fgithub.com%2FKnpLabs%2Fphp-github-api
```

_Example response_
```
{
   	"full_name": "KnpLabs\/php-github-api",
   	"owner": "KnpLabs",
   	"name": "php-github-api",
   	"fields": {
   		    "forks": 455,
   		    "stars": 1369,
   		    "last_update": "2018-08-23T15:43:14Z",
   		    "open_pull_requests": 3,
   		    "closed_pull_requests": 466,
   		    "latest_release": "2018-07-24T17:28:22Z"
   	}
}
```

#### Repositories comparison
/api/repository/{identifier}?compare={identifier} - compares two repositories and provides information about a repository with a better score
```
http://localhost:8000/api/repository/https%3A%2F%2Fgithub.com%2Fgoogle%2Fpython-fire?compare=google%2Ffscrypt
```

_Example response_
```
{
    "repository_one": {
        "full_name": "google\/python-fire",
        "owner": "google",
        "name": "python-fire",
        "fields": {
            "forks": 656,
            "stars": 11835,
            "last_update": "2018-08-25T21:22:41Z",
            "open_pull_requests": 8,
            "closed_pull_requests": 45,
            "latest_release": "2018-02-23T18:29:53Z"
        }
	},
	"repository_two": {
        "full_name": "google\/fscrypt",
        "owner": "google",
        "name": "fscrypt",
        "fields": {
            "forks": 24,
            "stars": 215,
            "last_update": "2018-08-25T20:46:04Z",
            "open_pull_requests": 2,
            "closed_pull_requests": 50,
            "latest_release": "2018-08-23T18:16:28Z"
        }
	},
	"diff_summary": {
        "forks": {
            "google\/python-fire": 656
        },
        "stars": {
            "google\/python-fire": 11835
        },
        "last_update": {
            "google\/python-fire": "2018-08-25T21:22:41Z"
        },
        "open_pull_requests": {
            "google\/python-fire": 8
        },
        "closed_pull_requestes": {
            "google\/fscrypt": 50
        },
        "latest_release": {
            "google\/fscrypt": "2018-08-23T18:16:28Z"
        }
	}
}
```
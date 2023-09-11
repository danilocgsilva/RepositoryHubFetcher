# RepositoryHubFetcher
Fetches data from online Repository Hubs, like Github, Bitbucket or Gitlab.
## Usage

```php
use Danilocgsilva\RepositoryHubFetcher\GithubFetcher;

$gitRepositoryFetcher = new GithubFetcher();
$gitRepositoryFetcher->setGithubUserDirectory('your_github_name');

print("<ul>");
foreach($gitRepositoryFetcher->getRepos() as $repo) {
    print("<li>{$repo->getName()}</li>");
}
print("</ul>");
```

If you want to get commits from a repository:

```php
$gitRepositoryFetcher->getCommits($repository);
```
This will return an array with commits.

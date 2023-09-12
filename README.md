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

## Requests limit

Github limits the requests amount, and it is little when not logged. To log and get a larger requests threshold, you can log with:

```php
$gitRepositoryFetcher = new GithubFetcher();
$gitRepositoryFetcher->setUser('your_github_user');
$gitRepositoryFetcher->setPassword('your_github_password');
```

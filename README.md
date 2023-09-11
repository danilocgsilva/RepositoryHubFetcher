# RepositoryHubFetcher
Fetches data from online Repository Hubs, like Github, Bitbucket or Gitlab.
## Usage

```
use Danilocgsilva\RepositoryHubFetcher\GithubFetcher;

$gitRepositoryFetcher = new GithubFetcher();
$gitRepositoryFetcher->setGithubUserDirectory('your_github_repository_name');

print("<ul>");
foreach($gitRepositoryFetcher->getRepos() as $repo) {
    print("<li>{$repo->getName()}</li>");
}
print("</ul>");
```

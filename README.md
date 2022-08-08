# jtiong.dev

heavily inspired by https://commits.facepunch.com

this uses the GitLab API to display the commits and work I'm doing.

## How it does this

    1. A regular crontask runs which gathers all the commits in every project accessible by me in GitLab (everything)
    2. It then stores these commits into a database (done this way to reduce API calls to GitLab)
    3. These commits are then sorted from Most Recent, to Oldest - regardless of repository
    4. These are then displayed in a similar, paginated format to Facepunch's site (50 commits per page)


# Symfony Docker

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework,
with [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) inside!

## Requirements
Please read the requirements [here](requirements.md).

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. If not already done, install `make` with `sudo apt-get install build-essential` (contains other packages as well) or simply `sudo apt-get -y install make`
3. Run `make start` to build fresh images
4. Run `make sh` to connect to PHP Container
5. Run `bin/console app:commission:calculate input.txt` to do commission calculations for the selected file.

## Makefile commands
Use these commands to simplify day to day usage of the project.
- `help`: Outputs this help screen
- `build`: Builds the Docker images
- `up`: Start the docker hub in detached mode (no logs)
- `start`: Build and start the containers
- `down`: Stop the docker hub
- `logs`: Show live logs
- `sh`: Connect to the FrankenPHP container
- `test`: Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
- `composer`: Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
- `vendor`: Install vendors according to the current composer.lock file
- `sf`: List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
- `cc`: Clear the cache
- `phpcs`: Run PHP Code Sniffer
- `phpmd`: Run PHP Mess detector
- `phpstan`: Run PHPStan


## Not implemented, improvements
Some suggestions in improving the functionality
- better error handling: instead of returning empty arrays/nulls when something goes wrong, problems should be addressed
appropriately, for example
  - on API calls that do not return 200, log the error and improve output
  - when the SplFile object throws an error, handle it accordingly
  - don't fail the whole execution if an API call goes wrong in the middle of processing the text file, instead log error
  and show an appropriate error message for that transaction
- add caching to API services: calling the same resource should not tax the external API, simple Redis container should
suffice, decorate services accordingly
- improve output message, it is horrible this way (it does comply with requirements though)

## Unnecessary project shenanigans
For simplifying development process, the project's base is coming from https://github.com/dunglas/symfony-docker, hence
it has some unnecessary features, such as a web server. The upside is that it only needs docker installed on a dev's
machine to be fully functional

## Other considerations
- changed the BIN lookup API, since the original from the requirements gives a 429 on consecutive calls, solution could
not have been tested with it

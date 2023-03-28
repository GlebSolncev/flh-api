default: help

ifeq (cli, $(firstword $(MAKECMDGOALS)))
  runargs := $(wordlist 2, $(words $(MAKECMDGOALS)), $(MAKECMDGOALS))
  $(eval $(runargs):;@true)
endif

.PHONY: help
help: # Show help for each of the Makefile recipes.
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done

.PHONY: up
up: # Start container
	docker-compose up -d

.PHONY: cli
cli: # Open cli container. List: backend, frontend, database. Example: make cli backend
	docker-compose exec -ti $(runargs) /bin/bash

.PHONY: build
build: # Build container
	docker-compose build --no-cache
	docker-compose run backend composer install
	docker-compose run backend ./vendor/bin/phinx migrate

.PHONY: projects-import
projects-import: # Import projects from Freelancehunt
	docker-compose run backend ./vendor/bin/phinx seed:run



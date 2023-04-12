# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up -d

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

up-with-xdebug: ## Runs docker containers with enabled Xdebug (only Linux and Mac)
	XDEBUG_MODE=debug docker-compose up -d

up-with-coverage: ## Runs docker containers with Xdebug coverage mode (only Linux and Mac)
	XDEBUG_MODE=coverage docker-compose up -d

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

migrations-diff:
	@$(SYMFONY) doctrine:migrations:diff

migrations-migrate:
	@$(SYMFONY) d:m:m

## —— Tools 🚀 ——————————————————————————————————————————————————————————————
cs-fix:
	$(PHP_CONT) vendor/bin/php-cs-fixer fix -v --allow-risky=yes

php-stan:
	$(PHP_CONT) vendor/bin/phpstan analyse

php-stan-baseline:
	$(PHP_CONT) vendor/bin/phpstan analyse --generate-baseline

## —— Testing` 🚀 ——————————————————————————————————————————————————————————————
test-unit:
	$(PHP_CONT) php bin/phpunit

test-unit-coverage: ## Works only if application was started with XDEBUG_MODE=coverage env variable (see make up-with-coverage)
	$(PHP_CONT) bin/phpunit --coverage-text

test-unit-coverage-report: ## Works only if application was started with XDEBUG_MODE=coverage env variable (see make up-with-coverage)
	$(PHP_CONT) bin/phpunit --coverage-html tests/.coverage

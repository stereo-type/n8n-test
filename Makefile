.PHONY: help install update cache-clear lint phpstan cs cs-fix test test-coverage migrate migration

# Colors
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
RESET  := $(shell tput -Txterm sgr0)

## —— Help ——————————————————————————————————————————————————————————————
help: ## Show this help message
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Composer —————————————————————————————————————————————————————————
install: ## Install PHP dependencies
	composer install --no-interaction --prefer-dist

update: ## Update PHP dependencies
	composer update --no-interaction --prefer-dist

## —— Symfony ——————————————————————————————————————————————————————————
cache-clear: ## Clear Symfony cache
	php bin/console cache:clear

## —— Database —————————————————————————————————————————————————————————
migration: ## Generate a new migration
	php bin/console make:migration

migrate: ## Run database migrations
	php bin/console doctrine:migrations:migrate --no-interaction

migrate-rollback: ## Roll back last migration
	php bin/console doctrine:migrations:migrate prev --no-interaction

schema-validate: ## Validate the doctrine mapping
	php bin/console doctrine:schema:validate

## —— Tests ————————————————————————————————————————————————————————————
test: ## Run PHPUnit tests
	php vendor/bin/phpunit --colors=always

test-coverage: ## Run tests with code coverage
	XDEBUG_MODE=coverage php vendor/bin/phpunit --coverage-html var/coverage --colors=always

## —— Code Quality —————————————————————————————————————————————————————
phpstan: ## Run PHPStan static analysis
	php vendor/bin/phpstan analyse --memory-limit=256M

cs: ## Check code style
	php vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix: ## Fix code style
	php vendor/bin/php-cs-fixer fix

lint: ## Run all linters
	@make phpstan
	@make cs

## —— Security —————————————————————————————————————————————————————————
security-check: ## Check for known security vulnerabilities
	composer audit

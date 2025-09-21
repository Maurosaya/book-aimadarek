# Booking System Makefile
# Provides convenient commands for development and deployment

.PHONY: help install up down migrate seed test clean

# Default target
help: ## Show this help message
	@echo "Booking System - Available Commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Development commands
install: ## Install dependencies and setup the project
	composer install
	cp .env.example .env
	php artisan key:generate
	@echo "Project installed! Run 'make up' to start the services."

up: ## Start all services with Docker Compose
	docker-compose up -d
	@echo "Services started! Run 'make migrate' to setup the database."

down: ## Stop all services
	docker-compose down

# Database commands
migrate: ## Run database migrations
	docker-compose exec app php artisan migrate --force
	docker-compose exec app php artisan tenants:migrate --force

seed: ## Seed the database with demo data
	docker-compose exec app php artisan db:seed --force
	docker-compose exec app php artisan tenants:seed --force

fresh: ## Fresh migrate and seed
	docker-compose exec app php artisan migrate:fresh --force
	docker-compose exec app php artisan tenants:migrate --force
	docker-compose exec app php artisan db:seed --force
	docker-compose exec app php artisan tenants:seed --force

# Testing commands
test: ## Run tests
	docker-compose exec app php artisan test

test-coverage: ## Run tests with coverage
	docker-compose exec app php artisan test --coverage

# Queue commands
queue: ## Start the queue worker
	docker-compose exec app php artisan queue:work

horizon: ## Start Horizon
	docker-compose exec app php artisan horizon

# Cache commands
cache: ## Clear all caches
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

# Development utilities
shell: ## Open shell in app container
	docker-compose exec app bash

logs: ## Show logs
	docker-compose logs -f

clean: ## Clean up containers and volumes
	docker-compose down -v
	docker system prune -f

# Production commands
build: ## Build production images
	docker-compose -f docker-compose.prod.yml build

deploy: ## Deploy to production
	docker-compose -f docker-compose.prod.yml up -d
	docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
	docker-compose -f docker-compose.prod.yml exec app php artisan tenants:migrate --force

# Tenant management
create-tenant: ## Create a new tenant (usage: make create-tenant TENANT=ranch)
	@if [ -z "$(TENANT)" ]; then echo "Usage: make create-tenant TENANT=ranch"; exit 1; fi
	docker-compose exec app php artisan tenants:create $(TENANT)
	docker-compose exec app php artisan tenants:run $(TENANT) -- migrate --force
	docker-compose exec app php artisan tenants:run $(TENANT) -- db:seed --force

list-tenants: ## List all tenants
	docker-compose exec app php artisan tenants:list

# API testing
test-api: ## Test API endpoints
	@echo "Testing API endpoints..."
	@echo "1. Test availability endpoint:"
	@curl -s "http://localhost/api/v1/availability?service_id=1&date=2025-09-22" | jq .
	@echo ""
	@echo "2. Test booking endpoint:"
	@curl -s -X POST "http://localhost/api/v1/book" \
		-H "Content-Type: application/json" \
		-d '{"service_id":1,"start":"2025-09-22T19:00:00Z","customer":{"name":"Test User","email":"test@example.com"}}' | jq .

# Widget testing
test-widget: ## Test widget functionality
	@echo "Widget test page available at: http://localhost/widget-test.html"
	@echo "Embed the widget with:"
	@echo '<div id="reservas-widget" data-tenant="ranch" data-service="1" data-locale="en"></div>'
	@echo '<script async src="http://localhost/widget.js"></script>'

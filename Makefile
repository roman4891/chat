init: docker-down-clear docker-pull docker-build docker-up
up: docker-up
down: docker-down
restart: down up
lint: api-lint
test: api-test
test-unit-coverage: api-test-unit-coverage

api-lint:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer cs-check

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

build: build-gateway build-frontend build-api

build-gateway:
	docker --log-level=debug build --pull --file=gateway/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-gateway:${IMAGE_TAG} gateway/docker/production/nginx

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-frontend:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-api:${IMAGE_TAG} api

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

api-init: api-composer-install api-permissions

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-test:
	docker-compose run --rm api-php-cli composer test

api-test-functional:
	docker-compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-unit:
	docker-compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker-compose run --rm api-php-cli composer test-unit-coverage

api-clear:
	docker-compose run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/*'

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var


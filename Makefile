init: docker-down-clear \
	app-clear \
	docker-pull docker-build docker-up \
	app-init
app-init: app-permissions app-composer-install
down: docker-down-clear

docker-up:
	docker-compose up -d

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

app-clear:
	docker run --rm -v ${PWD}/app:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/*'

app-composer-install:
	docker-compose run --rm app-php-cli composer install

app-permissions:
	docker run --rm -v ${PWD}/app:/app -w /app alpine chmod 777 var/cache var/log
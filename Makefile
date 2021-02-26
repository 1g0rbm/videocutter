init: docker-down-clear \
	docker-pull docker-build \
	docker-up
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
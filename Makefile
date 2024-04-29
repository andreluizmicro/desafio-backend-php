CONTAINER_NAME=transfer-microservice-app

docker-build:
	docker compose build

docker-up:
	docker compose up -d

docker-down:
	docker compose down

docker-bash:
	 docker compose exec app bash

docker-test:
ifdef FILTER
	make docker-up-all
	make docker-clear
	docker exec -t $(CONTAINER_NAME) composer unit-test -- --filter="$(FILTER)"
else
	make docker-up-all
	make docker-clear
	docker exec -t $(CONTAINER_NAME) composer unit-test
endif

docker-up-all:
	$(COMMAND) --profile all up -d

docker-migrate:
	docker exec $(CONTAINER_NAME) php -d memory_limit=-1 artisan migrate

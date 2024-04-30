CONTAINER_NAME=transfer-microservice-app

docker-build:
	docker compose build

docker-up:
	docker compose up -d

docker-down:
	docker compose down

docker-bash:
	 docker compose exec app bash

docker-format: docker-up
	docker exec -t $(CONTAINER_NAME) composer format

docker-test:
ifdef FILTER
	docker exec -t $(CONTAINER_NAME) php artisan test --env=test --filter="$(FILTER)"
else
	docker exec -t $(CONTAINER_NAME) php artisan test
endif

docker-up-all:
	$(COMMAND) --profile all up -d

docker-migrate:
	docker exec $(CONTAINER_NAME) php -d memory_limit=-1 artisan migrate

docker-test-coverage: docker-up
	docker exec $(CONTAINER_NAME) composer test-coverage-html

generate-openapi-from-postman:
	sudo npm i postman-to-openapi -g
	sudo p2o ./docs/collection_postman.json -f ./docs/openapi.yml -o ./docs/openapi-options.json

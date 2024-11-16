test:
	docker compose run --rm cli php bin/phpunit

test-migration:
	docker compose run --rm cli bin/console doctrine:migrations:migrate --env=test

test-load-fixtures:
	docker compose run --rm cli bin/console doctrine:fixtures:load --env=test

test-drop-test-db:
	docker compose run --rm cli bin/console doctrine:database:drop --force --env=test

test-create-test-db:
	docker compose run --rm cli bin/console doctrine:database:create --env=test

test-create-test-schema:
	docker compose run --rm cli bin/console doctrine:schema:create --env=test

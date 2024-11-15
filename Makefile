test:
	docker compose run --rm cli php bin/phpunit

test-migration:
	docker compose run --rm cli bin/console doctrine:migrations:migrate --env=test

test-fixtures:
	docker compose run --rm cli bin/console doctrine:fixtures:load --env=test
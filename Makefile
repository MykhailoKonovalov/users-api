start:
	test -s .env && cp .env .env.local
	docker-compose -f compose.yaml build
	docker-compose -f compose.yaml up -d
	docker-compose -f compose.yaml exec php composer install
	docker-compose -f compose.yaml exec php php bin/console doctrine:database:create --if-not-exists
	#docker-compose -f compose.yaml exec php php bin/console doctrine:migrations:migrate --no-interaction

up:
	docker-compose -f compose.yaml up -d

down:
	docker-compose -f compose.yaml down
reboot:
	docker-compose down;
	docker-compose up -d;

migration:
	docker container exec -it  laravel_vue-app_1 bash

phptest:
	docker container exec -it  laravel_vue_app_1 bash -c "./vendor/bin/phpunit --testsuite=Feature"

bals:
	docker system prune -a
	docker volume rm laravel_vue_test-mysql-data;
	docker volume rm laravel_vue_mysql-data;
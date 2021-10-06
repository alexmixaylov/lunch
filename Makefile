init: install clear

clear:
	bin/console doctrine:database:drop --force
	bin/console doctrine:database:create
	bin/console doctrine:schema:update --force

install:
	composer install
	npm install


serve:
	bin/console server:start
	npm run dev-server

stop:
	bin/console server:stop


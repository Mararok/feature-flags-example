init_growthbook:
	cd ./growthbook; \
	docker-compose -p growthbook up -d
	@echo "Growthbook Panel: http://127.0.0.1:3000"

init_backend_php:
	cd ./app/backend_php; \
	composer install; \
	docker-compose -p backend_php up -d; \
	bin/console app:system:feature-flags:cache:refresh; \
	bin/console --env=prod cache:warmup; \
	bin/console --env=prod app:system:feature-flags:cache:refresh

init_frontend:
	cd ./app/frontend; \
	npm install

frontend_dev:
	cd ./app/frontend; \
    npm run dev

project_down:
	cd ./growthbook && docker-compose -p growthbook down
	cd ./app/backend_php && docker-compose -p backend_php down


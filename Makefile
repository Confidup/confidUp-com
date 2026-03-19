.PHONY: up down restart logs shell db-shell

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f wordpress

shell:
	docker compose exec wordpress bash

db-shell:
	docker compose exec db mysql -u $${DB_USER:-wordpress} -p$${DB_PASSWORD:-wordpress} $${DB_NAME:-wordpress}

COMPOSE_FILE := ./src/docker-compose.yml
DC := docker compose -f $(COMPOSE_FILE)

all:
	@sudo $(DC) up -d --build

down:
	@sudo $(DC) down

erase:
	@sudo docker ps -qa | xargs -r docker stop
	@sudo docker ps -qa | xargs -r docker rm
	@sudo docker images -qa | xargs -r docker rmi -f
	@sudo docker volume ls -q | xargs -r docker volume rm
	@sudo docker system prune -a --volumes -f

# Pendiente de actualizar
reset:
	@sudo rm -rf ~/data/mariadb ~/data/wordpress
	@sudo rm -rf ~/data
# Pendiente de actualizar

re: down all

nginx:
	@docker exec -it nginx /bin/bash

sqlite:
	@docker exec -it sqlite /bin/bash

recreate:
	@$(DC) up -d --build --force-recreate
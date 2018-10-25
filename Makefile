#!/bin/bash

U_ID=$(shell id -u)

dev:
	U_ID=${U_ID} docker-compose up -d
ssh-be:
	docker exec -it --user "${U_ID}" spotahome-app bash
ssh-db:
	docker exec -it spotahome-app-db bash
create-db:
	docker exec -it spotahome-app-db mysql -p -e "CREATE DATABASE spotahome_test"

# mastermind-api-kata

###How to get it running:

1. `cp .env.dist .env` and use the proper parameters.
2. `make dev` to build the docker containers
3. `make ssh-be` to get inside the docker container
4. `bin/console doctrine:database create` Please check first you have the proper parameters in your .env file
5. `bin/console doctrine:migrations:migrate` To get the tables created.
6. `php -S 0.0.0.0:8000 -t public` to serve the content.

To play around with the API use the following curl calls.

### Create a game POST /api/game
```
curl -X POST \
  http://localhost:8000/api/game \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'name=JoseTest001&max_attempts=4&combination=RED%2CGREEN%2CBLUE%2CBLACK'
```
It will return something like:
```
{"id":1,"uuid":"e9035add-ba19-4f5a-893d-fcbe9435a3ca","name":"JoseTest001","max_attempts":4,"guess_attempts":[],"finished":false}
```

### Make a guess POST /api/game/{uuid}/guess_attempt
```
curl -X POST \
  http://localhost:8000/api/game/e9035add-ba19-4f5a-893d-fcbe9435a3ca/guess_attempt \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d guess_attempt=BLACK%2CYELLOW%2CBLUE%2CBLACK
```
Which will return something like:

```
{"id":1,"uuid":"e9035add-ba19-4f5a-893d-fcbe9435a3ca","name":"JoseTest001","max_attempts":4,"guess_attempts":[{"uuid":"e9035add-ba19-4f5a-893d-fcbe9435a3ca","player_attempt":"BLACK,YELLOW,BLUE,BLACK","feedback":",,RED,RED"}],"finished":false}
```

Keep playing until you guess the combination or until you reach the max amount of attempts.

### Get game history GET /api/game/{uuid}
```
curl -X GET \
  http://localhost:8000/api/game/e9035add-ba19-4f5a-893d-fcbe9435a3ca \
  -H 'Content-Type: application/x-www-form-urlencoded' 
```

Which will return
```
{
    "id": 1,
    "uuid": "e9035add-ba19-4f5a-893d-fcbe9435a3ca",
    "name": "JoseTest001",
    "max_attempts": 4,
    "guess_attempts": [
        {
            "uuid": "e9035add-ba19-4f5a-893d-fcbe9435a3ca",
            "player_attempt": "BLACK,YELLOW,BLUE,BLACK",
            "feedback": ",,RED,RED"
        }
    ],
    "finished": false
}
```
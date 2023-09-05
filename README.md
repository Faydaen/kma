# Тестовое задание для KMA

## Установка и запуск

Создать файл .env по аналогии с .env.example
```bash
cp .env.example .env
```
Желательно поменять там пароли, так же, там можно переопределить внешние порты, на случай если на host машине такие порты уже заняты

Запустить docker compose
```bash
docker-compose run
```
Дождаться пока всё запуститься

Далее выполнить скрипт, который добавляет в очередь все url
```bash
docker exec -it app php sendUrlsToQueue.php
```

Затем выполнить скрипт, который обрабатывает очередь
```bash
docker exec -it app php processQueue.php
```

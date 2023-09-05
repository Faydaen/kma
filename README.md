# Тестовое задание для KMA

## Установка и запуск

Создать файл .env по аналогии с .env.example
```bash
cp .env.example .env
```

Запустить докер композер
```bash
docker-compose run
```
Дождаться пока всё запуститься

После этого зайти в контейнер app
```bash
docker exec -it app bash
```

Внутри контейнера app выполнить скрипт который добавляет в очередь все url
```bash
php sendUrlsToQueue.php 
```

Далее (так же внутри контейнера) выполнить скрипт который обрабатывает очередь
```bash
php processQueue.php
```


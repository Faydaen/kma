<?php

namespace App;

use Exception;

/**
 * @throws Exception
 */
function getContentLength(string $url): int
{
    $ch = curl_init($url);

    // параметры запроса
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // не выводим ничего (возвращаем в виде строки)
    curl_setopt($ch, CURLOPT_NOBODY, true); // не получаем тело запроса (длину получим из заголовка)
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); // ожидание соединения в секундах
    curl_setopt($ch, CURLOPT_TIMEOUT, 20); // ожидание ответа в секундах

    // делаем запрос
    $response = curl_exec($ch);

    // проверяем на ошибки
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("не удалось получить контент: $error");
    }

    // получим информацию о запросе
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // закрываем соединение
    curl_close($ch);

    // проверим статус ответа
    if ($httpCode >= 400) {
        throw new Exception("запрос завершился с HTTP-статусом $httpCode");
    }

    // получим длину контента из заголовков ответа
    $contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

    // в случае если не удается получить информацию из заголовков, получаем весь контент
    // и считает его длину в лоб
    if ($contentLength === false || $contentLength < 0) {
        $content = file_get_contents($url);
        $contentLength = strlen($content);
    }

    return $contentLength;
}

function insertInClickHouse($url, $contentLength): void
{
    $clickHouseClient = new ClickHouse();

    $safeContentLength = filter_var($contentLength, FILTER_VALIDATE_INT);
    $safeUrl = filter_var($url, FILTER_VALIDATE_URL);
    /** @noinspection SqlResolve */
    $sql = sprintf("INSERT INTO parse_results (ContentLength, Url) VALUES ('%s', '%s')", $safeContentLength, $safeUrl);

    $clickHouseClient->insert($sql);
}

function insertInMariaDB($url, $contentLength): void
{
    $sql = 'INSERT INTO parse_results (url, content_length) VALUES (:url, :content_length)';
    $params = [
        'url' => $url,
        'content_length' => $contentLength
    ];
    MariaDb::insert($sql, $params);
}

function makeMariaDbQuery(): void
{
    $sql = <<<SQL
SELECT
    DATE_FORMAT(parsed_at, '%i') AS minute,
    COUNT(*) AS row_count,
    AVG(content_length) AS avg_content_length,
    MIN(parsed_at) AS first_message_time,
    MAX(parsed_at) AS last_message_time
FROM
    parse_results
GROUP BY
    minute
ORDER BY
    minute;
SQL;

    echo '--------- Запрос к mariaDB: --------- ' . PHP_EOL;
    $result = MariaDb::query($sql);
    printQueryResult($result);
}

function makeClickHouseQuery(): void
{
    $clickHouseClient = new ClickHouse();
    /** @noinspection SqlResolve */
    $sql = 'SELECT toStartOfMinute(ParseDate) AS minute, count(*) AS row_count, avg(ContentLength) AS avg_content_length, min(ParseDate) AS first_message_time, max(ParseDate) AS last_message_time FROM parse_results GROUP BY minute ORDER BY minute';

    echo '--------- Запрос к clickHouse: --------- ' . PHP_EOL;
    $result = $clickHouseClient->query($sql);

    printQueryResult($result['data']);
}

function printQueryResult($result): void
{
    foreach ($result as $row) {
        echo 'Минута парса: ' . $row['minute'] . PHP_EOL;
        echo 'Количество строк: ' . $row['row_count'] . PHP_EOL;
        echo 'Средня длина контента: ' . $row['avg_content_length'] . PHP_EOL;
        echo 'Время записи первого сообщения в эту минуту: ' . $row['first_message_time'] . PHP_EOL;
        echo 'Время записи последнего сообщения в эту минуту: ' . $row['last_message_time'] . PHP_EOL;
        echo PHP_EOL;
    }
}

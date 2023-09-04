<?php

namespace App;

//
///**
// * Получить переменную окружения
// * @param string $name имя переменой окружения
// * @param string|null $default что подставить если такой переменой не найдено
// * @throws Exception
// */
//function env(string $name, string $default = null): string
//{
//    if (isset($_ENV[$name])) {
//        return $_ENV[$name];
//    }
//
//    if (!$default) {
//        throw new Exception('Не обнаружена переменная окружения ' . $name . ' и не было передано значение по умолчанию');
//    }
//
//    return $default;
//}

use Exception;

/**
 * @throws Exception
 */
function getContentLength(string $url): int
{
    $ch = curl_init($url);

    // настроим параметры запроса
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // ожидание соединения в секундах
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // ожидание ответа в секундах

    // выполняем запрос
    $response = curl_exec($ch);

    // проверим на наличие ошибок
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("Не удалось получить контент. Ошибка: $error");
    }

    // получим информацию о запросе
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // закрываем соединение
    curl_close($ch);

    // проверим статус ответа
    if ($httpCode >= 400) {
        throw new Exception("Запрос завершился с HTTP-статусом $httpCode");
    }

    // получим длину контента из заголовков ответа
    $contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

    if ($contentLength === false || $contentLength < 0) {
        throw new Exception("Не удалось получить длину контента");
    }

    return $contentLength;
}



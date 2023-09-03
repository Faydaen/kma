<?php

namespace App;

use Exception;

/**
 * Получить переменную окружения
 * @param string $name имя переменой окружения
 * @param string|null $default что подставить если такой переменой не найдено
 * @throws Exception
 */
function env(string $name, string $default = null): string
{
    if (isset($_ENV[$name])) {
        return $_ENV[$name];
    }

    if (!$default) {
        throw new Exception('Не обнаружена переменная окружения ' . $name . ' и не было передано значение по умолчанию');
    }

    return $default;
}

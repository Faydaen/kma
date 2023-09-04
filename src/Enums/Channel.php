<?php

namespace App\Enums;

enum Channel: string
{
    case PARSE_URL = 'parse_url';
    case SQL_QUERY = 'sql_query';
}

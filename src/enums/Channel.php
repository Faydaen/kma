<?php

namespace App\enums;

enum Channel: string
{
    case PARSE_URL = 'parse_url';
    case SQL_QUERY = 'sql_query';
}

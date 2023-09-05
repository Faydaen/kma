<?php

namespace App;

use CurlHandle;

class ClickHouse
{
    public function query($query): array
    {
        $ch = $this->getCurl();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query.' FORMAT JSON');

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Ошибка cURL: ' . curl_error($ch);
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    public function insert($query): void
    {
        $ch = $this->getCurl();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

        curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Ошибка cURL: ' . curl_error($ch);
        }

        curl_close($ch);
    }

    private function getCurl(): CurlHandle
    {
        $host = 'clickhouse';
        $port = '8123';
        $database = 'db';
        $username = getenv('CLICKHOUSE_USER');
        $password = getenv('CLICKHOUSE_PASSWORD');

        $url = "http://$username:$password@$host:$port?database=$database";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $ch;
    }
}

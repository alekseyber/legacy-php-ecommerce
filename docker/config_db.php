<?php

// Конфигурация БД для стенда в docker compose.
// Значения совпадают с docker-compose.yml; наружу база не публикуется.
return [
    'dsn'  => 'mysql:host=db;dbname=ishop;charset=utf8',
    'user' => 'ishop',
    'pass' => 'ishop',
];

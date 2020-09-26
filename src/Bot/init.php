<?php

require realpath(__DIR__ . '/../../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/../../'));
$dotenv->load();

$dotenv->required('BOT_AUTH_TOKEN')->notEmpty();
$dotenv->required('COMMAND_PREFIX')->notEmpty();


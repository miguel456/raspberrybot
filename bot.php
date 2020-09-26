<?php

use nogueiracodes\RaspberryBot\Bot\Actions\MessageLogger;
use nogueiracodes\RaspberryBot\Bot\Commands\SimplePlayback;
use nogueiracodes\RaspberryBot\Core\RaspberryBot;

require 'src/Bot/init.php';

$raspberryBot = new RaspberryBot();

echo 'RaspberryBot v.0.1.0. Loading commands and actions.' . PHP_EOL;
echo 'This console will now display logging output from actions and commands sent by users.' . PHP_EOL;

$raspberryBot
    ->initialize()
    ->addCommand(new SimplePlayback)
    ->addAction(new MessageLogger)
    ->setCommandPrefix($_ENV['COMMAND_PREFIX'])
    ->run();
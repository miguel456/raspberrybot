<?php

namespace nogueiracodes\RaspberryBot\Bot\Actions;

use nogueiracodes\RaspberryBot\Core\Interfaces\Action;

class MessageLogger implements Action
{
    public function processAction($message)
    {
        
        echo "Sniffed message from " . $message->author->username . ": " . $message->content . PHP_EOL;

    }

}
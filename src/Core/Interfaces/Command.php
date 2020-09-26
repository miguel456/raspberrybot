<?php

namespace nogueiracodes\RaspberryBot\Core\Interfaces;

use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;

interface Command 
{


    /**
     * Implement what the command should do here.
     *
     * @return void
     */
    public function run(array $parameters);

    

    /**
     * Obtains the signature in an array from, from the command's internal signature.
     *
     * @param string $signature
     * @return array
     */
    public function getCommandSignature(string $signature): array;


    /**
     * Same as getCommandSignature, but for convenience.
     * 
     * @see getCommandSignature()
     * @return array
     */
    public function getCommandArguments(): array;
}
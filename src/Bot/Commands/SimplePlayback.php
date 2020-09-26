<?php 

namespace nogueiracodes\RaspberryBot\Bot\Commands;

use nogueiracodes\RaspberryBot\Core\Interfaces\Command;
use nogueiracodes\RaspberryBot\Traits\HasSignature;

// Inspired by Laravel's artisan commands
class SimplePlayback implements Command
{
    use HasSignature;

    /**
     * Must be included at all times. This describes the command's parameters, which can be used in the run method.
     *
     * @var string
     */
    private $signature = "capitalize {word: The word you want to convert} {state: lower or upper}";


    /**
     * Parrots back whatever the user said. Proof of concept.
     *
     * @param string $parameters This is an array of named parameters with values, based on the signature. First value is always the command's name, like how PHP's $argv is organized.
     * @return void
     */
    public function run(array $parameters)
    {

        // Play with the string first before sending it
        switch($parameters['state'])
        {
            case 'lower':

                $capitalized = strtolower($parameters['word']);

                break;
            case 'upper':

                $capitalized = strtoupper($parameters['word']);

                break;

            default:
                $capitalized = "Usage: !!rb capitalize [word] [upper|lower]. Use !!rb help for more.";
        }

        return $capitalized;

    }

}
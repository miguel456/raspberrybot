<?php 

namespace nogueiracodes\RaspberryBot\Bot\Commands;

use Exception;
use nogueiracodes\RaspberryBot\Core\Interfaces\Command;
use nogueiracodes\RaspberryBot\Traits\HasSignature;
use Zttp\Zttp;

class NumberFact implements Command
{
    use HasSignature;


    public $signature = "numberfact {number: The number for which you want facts for.}";


    public function run(array $parameters)
    {
        
        $number = $parameters['number'];
        $result = Zttp::get('http://numbersapi.com/' . $number . '?json');

        if ($result->isOk())
        {
            return $result->json()['text'];
        }
        else
        {
            throw new Exception("Sorry, but I can\'t fetch number facts right now. Try again later.");
        }

    }
}
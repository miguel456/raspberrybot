<?php

namespace nogueiracodes\RaspberryBot\Traits;

use Illuminate\Support\Str;
use Discord\Parts\Channel\Message;
use Exception;
use nogueiracodes\RaspberryBot\Core\Interfaces\Command;

trait ParsesCommands
{ 

    /**
     * Checks if said message is a command.
     *
     * @param Message $message
     * @return boolean
     */
    private function isCommand(Message $message): bool
    {
        if (strtok($message->content, " ") == $this->commandPrefix)
            return true;
        
        return false;
    }

    
    private function determineCommand(Message $message): Command
    {
        $commandName = strtok(str_replace($this->commandPrefix, "", $message->content), " ");

        foreach ($this->commands as $command)
        {
            // Due to a bug, the command name is returned on all parameters. It doesn't matter which parameter we 
            // access to get the name, since they're all the same.
            if ($command->getCommandArguments()[0]['commandName'] == $commandName)
            {
                return $command;
            }
        }

        throw new \Exception("Unknown command (" . $commandName . "). Please try again or use {$this->commandPrefix} help.");
            
    }



    private function getNamedArguments(Message $message, Command $command): array
    {
       
        $commandArguments = [];
        $parameters = [];


        foreach($command->getCommandArguments() as $argument)
        {   
            array_push($commandArguments, $argument['argumentName']);
        }

        $incomingString = $message->content;
        $splicedMessage = preg_split('/\s+/', str_replace($this->commandPrefix, "", $incomingString), -1, PREG_SPLIT_NO_EMPTY);

        
        unset($splicedMessage[0]); // Get rid of the cmd name (always same index)
        sort($splicedMessage); // Reindex the array
        
        if (count($splicedMessage) !== count($commandArguments))
            throw new Exception("Invalid usage! Please supply the correct arguments. Use {$this->commandPrefix} help for more information.");

        return array_combine($commandArguments, $splicedMessage);
    }

}
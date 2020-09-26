<?php 

namespace nogueiracodes\RaspberryBot\Bot\Commands;

use Ely\Mojang\Api;
use Exception;
use nogueiracodes\RaspberryBot\Core\Interfaces\Command;
use nogueiracodes\RaspberryBot\Traits\HasSignature;

// Inspired by Laravel's artisan commands
class MinecraftInfo implements Command
{
    use HasSignature;

    /**
     * Must be included at all times. This describes the command's parameters, which can be used in the run method.
     *
     * @var string
     */
    private $signature = "minecraft {operation: The The kind of operation; for now, only convert is supported} {subOperationType: The sub operation type} {subOperationArgument: The argument you want to pass to to the sub operation}";


    /**
     * Parrots back whatever the user said. Proof of concept.
     *
     * @param string $parameters This is an array of named parameters with values, based on the signature. First value is always the command's name, like how PHP's $argv is organized.
     * @return void
     */
    public function run(array $parameters)
    {
        $mojangAPI = new Api();
        switch($parameters['operation'])
        {
            // convert to username
            case 'convert':

                switch($parameters['subOperationType'])
                {
                    case 'username':
                        
                        $response =  $parameters['subOperationType'] . "\'s UUID is " . $mojangAPI->usernameToUUID($parameters['subOperationArgument'])->getId();
                        break;

                    default:
                        throw new Exception("Sorry, but at the moment you can only convert usernames to UUIDs.");
                }

                break;
            
            case 'blacklist':
                
                switch($parameters['subOperationType'])
                {
                    case 'is-banned':
                        
                        $blockedServerCollection = $mojangAPI->blockedServers();
                        
                        $response = ($blockedServerCollection->isBlocked($parameters['subOperationArgument']))
                            ? 'Sorry! It appears that this server is indeed being blocked by Mojang. Users won\'t be able to join this Minecraft server using the official client.'
                            : 'A little bird told me that this server is NOT blocked by Mojang. Users can freely join this server.';

                        break;

                    default:
                        throw new Exception("Sorry, but at the moment you can only check if servers are banned.");
                }

                
                break;

            default:
                
                throw new Exception("Invalid usage! Please refer to !!rb help for more information.");

        }

        return $response;

    }

}
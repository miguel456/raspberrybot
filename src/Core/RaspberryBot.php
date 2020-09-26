<?php declare(strict_types = 1);

namespace nogueiracodes\RaspberryBot\Core;

use Discord\Discord;
use Discord\DiscordCommandClient;
use Exception;
use nogueiracodes\RaspberryBot\Core\Interfaces\Action;
use nogueiracodes\RaspberryBot\Core\Interfaces\Command;
use nogueiracodes\RaspberryBot\Traits\ParsesCommands;

class RaspberryBot 
{
    use ParsesCommands;

    /**
     * The Discord Command Client.
     *
     * @var \Discord\DiscordCommandClient;
     */
    private $commandClient;

    
    /**
     * The loaded commands for this bot instance.
     *
     * @var array
     */
    private $commands = [];


    
    /**
     * The loaded actions for this bot instance. Actions run on messages sent by users, and not when a prefix is detected, unlike commands.
     *
     * @var array
     */
    private $actions = [];




    /**
     * The command prefix the bot should look for when parsing commands.
     *
     * @var string
     */
    private $commandPrefix;

    

    /**
     * Stards the Discord Client and registers commands. Allows fluent chaining.
     *
     * @return void
     */
    public function initialize(): RaspberryBot
    {

        $this->commandClient = new DiscordCommandClient([
            'token' => $_ENV['BOT_AUTH_TOKEN'],
            'description' => 'A helpful robot giving you access to the Staff Manager Web App.',
        ]);
        return $this;
    }


    /**
     * Sets the command prefix the bot responds to.
     *
     * @param string $prefix The prefix.
     * @return RaspberryBot
     */
    public function setCommandPrefix(string $prefix): RaspberryBot
    {
        $this->commandPrefix = $prefix;
        return $this;
    }


    /**
     * Adds a command the bot will recognise. Must implement the Command interface.
     *
     * @param Command $command
     * @return RaspberryBot
     */
    public function addCommand(Command $command): RaspberryBot
    {
        array_push($this->commands, $command);
        return $this;
    }



    /**
     * Adds an action the bot will work on.
     * Actions run on messages, and they can be used to detect swearing, or reply to a user saying hello, etc.
     *
     * @param Action $action The action.
     * @return RaspberryBot
     */
    public function addAction(Action $action): RaspberryBot
    {
        array_push($this->actions, $action);
        return $this;
    }


    /**
     * Runs the event loop, registers commands, and starts listening for mesesages and processing actions
     *
     * @return void
     */
    public function run(): void
    {
        $this->commandClient->on('ready', function()
        {
            
            $this->commandClient->on('message', function($message)
            {   
                if (!empty($this->commands) && $this->isCommand($message))
                {
                    $reply = "";

                    try
                    {
                        $command = $this->determineCommand($message);
                        $arguments = $this->getNamedArguments($message, $command);
                        
                        $reply = $command->run($arguments);
                    }
                    catch (Exception $ex)
                    {
                        $reply = $ex->getMessage();
                    }

                    return $message->reply($reply);
                }


                if (!empty($this->actions))
                {
                    foreach($this->actions as $action)
                    {
                        $action->processAction($message);
                    }
                }
                else
                {
                    echo "(Debug) WARNING: No actions have been defined. However, we received a message, but don\'t know what to do with it.";
                }


            });          


        });


        $this->commandClient->run();
    }
}
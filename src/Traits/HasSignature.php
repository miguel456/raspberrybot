<?php

namespace nogueiracodes\RaspberryBot\Traits;

trait HasSignature 
{
    // sample signature
    // "commandName {argument: description} {argument2: description}";
    
    public function getCommandSignature(string $signature): array
    {
        $argumentList = [];


        $firstArgPos = strpos($signature, '{');
        $commandName = substr($signature, 0, $firstArgPos - 1);

        $hasRemainingArguments = true;
        $argumentCounter = 0;
        $executionTimer = 0;


        while($hasRemainingArguments)
        {
            // Bug check: This is an hardcoded parameter limit.
            // The bot will purposefully crash if it finds a command that defined more than twenty parameters,
            // or if somehow an unknown bug caused an infinite loop.
            // This can happen if something (or someone) messes with the internal counters
            // This piece of code can be safely removed if there are unit tests ensuring this won't happen. (insecurity lol)
            $executionTimer++;
            if ($executionTimer > 20)
               die('FATAL: Command signature parser - Infinite loop detected!');


            // Here we conditionally set the current argument bound start.
            // It can't be rewritten if it already has a value, which had been set to allow for the next iteration.
            if (!isset($currentArgBoundStart))
            {
              $currentArgBoundStart = $firstArgPos;
            }

            /*
            * An argument bound is defined by each curly bracket, e.g. Start and End {}.
            * Here it's position and value is recorded for later use.
            * An argument namespace is everything inside the curly brackets, including the brackets themsevles, e.g.
            * {test: description} <-- arg namespace
            */
            $currentArgBoundEnd = strpos($signature, '}', $currentArgBoundStart);
            $currentArgNamespace = substr($signature, $currentArgBoundStart , $currentArgBoundEnd - $currentArgBoundStart + 1);

            // Here we obtain the name and description values inside the namespace, basing ourselves off of the current namespace and the whole signature.
            $currentArgName = substr($signature, $currentArgBoundStart, strpos($currentArgNamespace, ':'));
            $currentArgDescription = substr($currentArgNamespace, strpos($currentArgNamespace, ':') + 2, strpos($currentArgNamespace, '}') - strlen($currentArgNamespace));

            // The next arg bound position is the start of the next arg bound, e.g. an opening curly bracket,
            // that will define the next namespace to scan.
            $nextArgBoundPos = strpos($signature, '{', $currentArgBoundEnd + 1);
            array_push($argumentList, [
                'commandName' => $commandName,
                'argumentName' => str_replace("{", "", $currentArgName),  //FIXME: This is a cheap workaround hack, the first iteration always has that character and the bug is somewhere above
                'argumentDescription' => $currentArgDescription,
                'argumentPosition' => $argumentCounter
            ]);
            $argumentCounter++;
                
            // Here we check if we can find the next namespace.
            // strpos() will always return false if it doesn't find what you told it to find.
            // Following that logic, a non integer value means no other namespace is here and therefore we quit the loop. 
            if (!is_int($nextArgBoundPos))
            {
                $hasRemainingArguments = false;
            }
            else
            {
                // To prevent an infinite PC crashing loop, we reset the current loop argument namespace's bounds to the next namespace.
                // Not doing this will cause an infinite loop where the function tries to parse the first namespace forever, because 
                // in this scenario there would always be a next namespace, causing the loop to not be exited.
                $currentArgBoundStart = $nextArgBoundPos + 1;
                $currentArgBoundEnd = strpos($signature, '}', $currentArgBoundStart) + 1;

            }
        }
        return $argumentList;
    }



    public function getCommandArguments(): array
    {
        return $this->getCommandSignature($this->signature);
    }


}
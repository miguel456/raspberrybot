<?php

namespace  nogueiracodes\RaspberryBot\Core\Interfaces;

interface Action
{
    /**
     * Process the said message. Can be used for cursing filters, etc.
     *
     * @param stdClass $message
     * @return void
     */
    public function processAction($message);

}
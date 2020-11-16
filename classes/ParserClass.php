<?php

namespace Classes;

class ParserClass {
    private $parse;
    private $current;
    private $file;
    private $messages;
    private $driver;

    public function __construct(object $parse, object $telegram) { 
        $this->parse = $parse; 
        $this->file = 'cache' . DS . $this->parse->file . '.json';

        $this->driver = $telegram;
    }

    private function getAndCheckNewMessage()
    {
        $this->current = $this->parse->getInfo();

        if (!file_exists($this->file)) {
            $this->messages = $this->current;
        } else {
            $this->messages = array_diff_assoc($this->current, json_decode(file_get_contents($this->file), true));
        }

        $this->saveCache();

        return (count($this->messages) > 0) ? $this->messages : []; 
    }

    public function sendTelegram()
    {
        $tasks = $this->getAndCheckNewMessage();

        if (count($tasks) > 0) {
            $messages = $this->parse->getTemplates($tasks);

            foreach ($messages as $message) {
                $this->driver->sendMessage(['chat_id' => TELEGRAM_CHAT_ID, 'text' => mb_convert_encoding($message, 'utf-8', mb_detect_encoding($message))]);
            }
        }
    }

    private function saveCache()
    {
       file_put_contents(ROOT . DS . $this->file, json_encode($this->current));
    }
}


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
            $header = $this->parse->getTemplateHead($tasks);
            $body = $this->parse->getTemplateBody($tasks);

            if (count($header) == count($body)) {
                for ($i = 0; $i < count($header); $i++) {
                    $this->driver->sendMessage(['chat_id' => TELEGRAM_CHAT_ID, 'text' => mb_convert_encoding($header[$i], 'utf-8', mb_detect_encoding($header[$i])), 'parse_mode' => 'html']);
                    $this->driver->sendMessage(['chat_id' => TELEGRAM_CHAT_ID, 'text' => mb_convert_encoding($body[$i], 'utf-8', mb_detect_encoding($body[$i]))]);
                }
            }
        }
    }

    private function saveCache()
    {
       file_put_contents(ROOT . DS . $this->file, json_encode($this->current));
    }
}


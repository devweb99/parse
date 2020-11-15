<?php

namespace Classes;

class ParserClass {
    private $parse;
    private $current;
    private $file;
    private $messages;

    public function __construct(object $parse) { 
        $this->parse = $parse; 
        $this->file = 'cache' . DS . $this->parse->file . '.json';
    }

    public function getAndCheckNewMessage()
    {
        $this->current = $this->parse->getInfo();

        if (!file_exists($this->file)) {
            $this->messages = $this->current;
        } else {
            $this->messages = array_diff_assoc($this->current, json_decode(file_get_contents($this->file), true));
        }

        $this->saveCache();

        return (count($this->messages) > 0) ? $this->messages : false; 
    }

    private function saveCache()
    {
       file_put_contents($this->file, json_encode($this->current));
    }
    
}

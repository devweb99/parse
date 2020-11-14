<?php

namespace Parses;

use Rct567\DomQuery\DomQuery;

class LectoriumartClass {
    private $base = 'https://lectoriumart.ru';
    private $dom;

    public function __construct (string $uri = '') {
        $this->dom = new DomQuery(file_get_contents($this->base . $uri)) ?? null; 

        var_dump($this->dom);
    }

    public function getPreviews ()
    {

    }

    public function getDetail ()
    {

    }    

    public function getFull ()
    {

    }
}


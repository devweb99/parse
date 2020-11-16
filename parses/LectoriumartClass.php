<?php

namespace Parses;

use Rct567\DomQuery\DomQuery;

class LectoriumartClass {
    private $base = 'http://lectoriumart.ru';
    private $dom;
    private $taskList = [];
    public $file;

    public function __construct(string $uri = '') {
        $this->dom = new DomQuery(file_get_contents($this->base . $uri)); 
        $this->file = preg_replace('#((http|https):\/\/)|(\.(ru|com|СЂС„|online|org))#', '', $this->base);

        $this->getTaskList();
    }

    private function getTaskList()
    {
        $this->dom->find('.products.columns-1 > li.product_cat-vne-raspisaniya')->remove();

        foreach ($this->dom->find('.products.columns-1 > li') as $link) {
            $this->taskList[] = $link->find('a')->attr('href');
        }
    }

    public function getInfo()
    {
        $info = [];

        foreach ($this->taskList as $task) {
            $dom = new DomQuery(file_get_contents($task));
            
            $info[] = [
                'link' => $task,
                'title' => trim($dom->find('.container article div.h3')->text()) ?? false,
                'when' => trim($dom->find('.container article .col-xl-4 .mb-1')->text()) ?? false,
                'where' => trim($dom->find('.container article .col-xl-4 .mb-1')->next()->text()) ?? false,
                'time' => trim($dom->find('.container article .col-xl-4 .mb-1')->next()->next()->text()) ?? false,
                'author' => trim($dom->find('.container .row .col-xl-5.col-8 a')->text()) ?? false,
                'discraption' => trim($dom->find('.container article .row .col-xl-5 > div')->next()->text()) ?? false
                
            ];
        }

        return $info;
    }

    public function getTemplates(array $tasks)
    {                                                                                                                      
        $templates = [];                                                                                                   
                                                                                      
        foreach ($tasks as $task) {                                                                                                                      
            $templates[] = "                                                                                                                         
                Новая тема \n
                {$task['title']} [ {$task['author']} ] \n                                                                                         
                {$task['discraption']} \n                                                                                                         
                {$task['author']} \n                                                                                                                 
                {$task['when']} \n                                                                                                                   
                {$task['link']}  
            ";                                                                                                                                       
        }
    
        return $templates;                                                                                                                               
    }
}


<?php

namespace Parses;

use Rct567\DomQuery\DomQuery;

class LectoriumartClass {
    private $base = 'http://lectoriumart.ru';
    private $dom;
    private $taskList = [];
    public $file;

    public function __construct(string $uri = '') {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $this->base . $uri, [
            'headers' => [
            'User-Agent' => 'Mozilla/5.0 (X11; Linux ppc64le; rv:75.0) Gecko/20100101 Firefox/75.0',
            ]
        ]);

        $html = $res->getBody()->getContents();
        $this->dom = new DomQuery($html); 
        $this->file = preg_replace('#((http|https):\/\/)|(\.(ru|com|рф|online|org))#', '', $this->base);

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
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $task, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux ppc64le; rv:75.0) Gecko/20100101 Firefox/75.0',
                ]
            ]);

            $html = $res->getBody()->getContents();
            $dom = new DomQuery($html);
            
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

    public function getTemplateHead(array $tasks)
    {                                                                                                                      
        $templates = [];                                                                                                   

        foreach ($tasks as $task) {                                                                                                                      
            $templates[] = "<b>Новая тема!!!</b>\n\n<a href='http://forum.com/create-thread?title=" . $task['title'] . "'>" . $task['title'] . "</a> [ {$task['author']} ]";        
        }
    
        return $templates;                                                                                                                               
    }

    public function getTemplateBody(array $tasks)
    {                                                                                                                      
        $templates = [];                                                                                                   
                                                                                      
        foreach ($tasks as $task) {                                                                                                                      
            $templates[] = "{$task['discraption']}\n\n{$task['author']} \n{$task['when']} \n{$task['link']}";                                                                                                                                       
        }
    
        return $templates;                                                                                                                               
    }
}


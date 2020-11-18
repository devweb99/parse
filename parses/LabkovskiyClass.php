<?php

namespace Parses;

use Rct567\DomQuery\DomQuery;

class LabkovskiyClass {
    private $base = 'https://labkovskiy.ru';
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
        $this->dom->find('script')->remove();

        foreach ($this->dom->find('.eo-events.eo-events-widget > li') as $link) {
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
                'title' => trim($dom->find('.eventorganiser-event-meta > h4')->text()) ?? false,
                'time' => $dom->find('.eo-event-meta li > strong')->remove() . trim($dom->find('.eo-event-meta')->text()) ?? false,
                'author' => 'Михаил Лабковский',
                'description' => $dom->find('.entry-content center, .entry-content .eo-event-meta')->remove() . strstr(preg_replace('#\n\n#', '', trim($dom->find('.entry-content')->text())), 'Внимание!', true)
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
            $templates[] = "{$task['description']}\n\n{$task['author']} \n{$task['time']} \n{$task['link']}";   
        }
    
        return $templates;                                                                                                                               
    }
}


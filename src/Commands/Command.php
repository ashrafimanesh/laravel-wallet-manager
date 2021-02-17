<?php


namespace Ashrafi\WalletManager\Commands;


class Command
{
    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var bool
     */
    public $dispatchEvent;

    public $events = [];

    public function __construct($dispatchEvent = true){
        $this->dispatchEvent = $dispatchEvent;
    }

    public function addMessage($message, $params = [], $type = 'error'){
        $class = $this;
        if(!isset($this->messages[$type])){
            $this->messages[$type] = [];
        }
        $msg = $message;
        foreach($params as $param=>$value){
            $str = '{' . $param . '}';
            if(strpos($message, $str)!==false){
               $message = str_replace($str, $value, $message);
            }
        }

        $this->messages[$type][] = compact('msg', 'message', 'params', 'class');
        return $this;
    }

    public function addEvent($event){
        $this->events[] = $event;
    }

    /**
     * @param null $type
     * @return array
     */
    public function getMessages($type = null): array
    {
        return $type ? ($this->messages[$type] ?? []) : ($this->messages ?? []);
    }

    public function getMessage($type = 'error')
    {
        return last($this->getMessages($type));
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

}

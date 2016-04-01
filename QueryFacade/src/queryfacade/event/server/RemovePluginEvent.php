<?php

namespace queryfacade\event\server;

use pocketmine\event\server\ServerEvent;
use queryfacade\network\DummyPlugin;

class RemovePluginEvent extends ServerEvent{
    /** @var \pocketmine\event\HandlerList */
    public static $handlerList = null;
    /** @var DummyPlugin */
    private $plugin;
    /**
     * @param DummyPlugin $plugin
     */
    public function __construct(DummyPlugin $plugin){
        $this->plugin = $plugin;
    }
    /**
     * @return DummyPlugin
     */
    public function getPlugin(){
        return $this->plugin;
    }
}
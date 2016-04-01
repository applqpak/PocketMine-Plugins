<?php

namespace queryfacade\network;

use pocketmine\Player;
use pocketmine\Server;
use queryfacade\network\DummyInterface;

class DummyPlayer extends Player{
    /** @var string */
    protected $username;
    /** @var string */
    protected $ip;
    /** @var int */
    protected $port;
    /** @var Server */
    protected $server;
    /** @var DummyInterface */
    protected $interface;
    /**
     * @param string $username
     * @param string $ip
     * @param int $port
     */
    public function __construct($username, $ip = "DUMMY", $port = 19132){
        $this->username = (string) $username;
        $this->ip = $ip; //Not necessarily required for what it'll be used for, but I'll just add this
        $this->port = (int) $port; //Not necessarily required for what it'll be used for, but I'll just add this
        $this->server = Server::getInstance();
        $this->interface = new DummyInterface();
    }
}
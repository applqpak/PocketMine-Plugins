<?php

namespace rapidcmd\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class RCMD extends Command{
    /** @var string[] */
    private $actions;
    /**
     * @param string $name
     * @param string $description
     * @param string $usage
     * @param string[] $aliases
     * @param string[] $actions
     */
    public function __construct($name, $description = "", $usage = null, array $aliases = [], array $actions = []){
        parent::__construct($name, $description, $usage, $aliases);
        $this->setPermission("rcmd.".strtolower($name));
        $this->actions = $actions;
    }
    /**
     * @param CommandSender $sender
     * @param string $label
     * @param string[] $args
     */
    public function execute(CommandSender $sender, $label, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
    }
    /**
     * @return string[]
     */
    public function getActions(){
        return $this->actions;
    }
    /**
     * @param string[] $actions
     */
    public function setActions(array $actions){
        $this->actions = $actions;
    }
}
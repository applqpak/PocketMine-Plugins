<?php

namespace domath;

use domath\command\DoMathCommand;
use pocketmine\plugin\PluginBase;

class DoMath extends PluginBase{
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("domath", new DoMathCommand($this));
    }
}
<?php

namespace domath\command;

use domath\utils\BasicCalculator;
use domath\DoMath;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class DoMathCommand extends Command{
    /** @var DoMath */
    private $plugin;
    /**
     * @param DoMath $plugin
     */
    public function __construct(DoMath $plugin){
        parent::__construct("domath", "Shows all DoMath commands", null, ["dm"]);
        $this->setPermission("domath.command.domath");
        $this->plugin = $plugin;
    }
    /**
     * @param CommandSender $sender
     */
    public function sendCommandHelp(CommandSender $sender){
        $commands = [
            "add" => "Calculates sum of numbers",
            //"carea" => "Calculates area of a circle",
            "divide" => "Calculates quotient of numbers",
            "exponent" => "Calculates exponent of numbers",
            "help" => "Shows all DoMath commands",
            "multiply" => "Calculates product of numbers",
            //"parea" => "Calculates area of a parallelogram",
            "percent" => "Calculates percentage difference of numbers",
            //"pvolume" => "Calculates the volume of a parallelogram",
            "square" => "Calculates square root of a number",
            "subtract" => "Calculates the difference of numbers",
            //"svolume" => "Calculates the volume of a sphere",
            //"tarea" => "Calculates the area of a triangle"
        ];
        $sender->sendMessage("DoMath commands:");
        foreach($commands as $name => $description){
            $sender->sendMessage("/domath ".$name.": ".$description);
        }
    }
    /**
     * @param CommandSender $sender
     * @param string $label
     * @param string[] $args
     * @return bool
     */
    public function execute(CommandSender $sender, $label, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(isset($args[0])){
            $failMessage = $this->plugin->getConfig()->get("failMessage");
            switch(strtolower($args[0])){
                case "a":
                case "add":
                    if(count(array_slice($args, 1)) > 1){
                        $sender->sendMessage(BasicCalculator::toString(array_slice($args, 1), BasicCalculator::ADD));
                    }
                    else{
                        $sender->sendMessage($failMessage);
                    }
                    return true;
                case "d":
                case "divide":
                    if(count(array_slice($args, 1)) > 1){
                        $sender->sendMessage(BasicCalculator::toString(array_slice($args, 1), BasicCalculator::DIVIDE));
                    }
                    else{
                        $sender->sendMessage($failMessage);  
                    }
                    return true;
                case "e":
                case "exponent":
                    if(count(array_slice($args, 1)) === 2){
                        $sender->sendMessage(BasicCalculator::toString(array_slice($args, 1), BasicCalculator::EXPONENT));
                    }
                    else{
                        $sender->sendMessage($failMessage);
                    }
                    return true;
                case "help":
                    $this->sendCommandHelp($sender);
                    break;
                case "m":
                case "multiply":
                    if(count(array_slice($args, 1)) > 1){
                        $sender->sendMessage(BasicCalculator::toString(array_slice($args, 1), BasicCalculator::MULTIPLY));
                    }
                    else{
                        $sender->sendMessage($failMessage);
                    }
                    return true;
                case "p":
                case "percent":
                    if(count(array_slice($args, 1)) === 2){
                        $sender->sendMessage(BasicCalculator::toString(array_slice($args, 1), BasicCalculator::PERCENT));
                    }
                    else{
                        $sender->sendMessage($failMessage);
                    }
                    return true;
                case "sq":
                case "square":
                    if(isset($args[1])){
                        $sender->sendMessage(BasicCalculator::toString($args[1], BasicCalculator::SQUARE));
                    }
                    else{
                        $sender->sendMessage($failMessage);
                    }
                    return true;
                case "s":
                case "subtract":
                    if(count(array_slice($args, 1)) > 1){
                        $sender->sendMessage(BasicCalculator::toString(array_slice($args, 1), BasicCalculator::SUBTRACT));
                    }
                    else{
                        $sender->sendMessage($failMessage);
                    }
                    return true;
                default:
                    $sender->sendMessage("Usage: /domath <sub-command> [parameters]");
                    return false;
            }
        }
        else{
            $this->sendCommandHelp($sender);
            return false;
        }
    }
}
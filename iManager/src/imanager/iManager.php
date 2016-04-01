<?php

namespace imanager;

use imanager\command\iManagerCommand;
use imanager\event\iManagerListener;
use imanager\utils\InfoFetcher;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Player;

class iManager extends PluginBase{
    /** @var Config */
    private $exempts;
    /** @var Config */
    private $ip;
    public function onEnable(){
        $this->saveDefaultConfig();
        @mkdir($this->getDataFolder());
    	$this->exempts = new Config($this->getDataFolder()."exempts.txt", Config::ENUM);
    	$this->ip = new Config($this->getDataFolder()."ip.txt", Config::ENUM);
    	$this->getServer()->getCommandMap()->register("imanager", new iManagerCommand($this));
    	$this->getServer()->getPluginManager()->registerEvents(new iManagerListener($this), $this);
    }
    /**
     * @return InfoFetcher
     */
    public function getInfoFetcher(){
        return new InfoFetcher($this);
    }
    /**
     * @param string $address
     * @return bool
     */
    public function isAddressWhitelisted($address){
    	return $this->ip->exists($address, true);
    }
    /**
     * @param Player $player
     * @param bool $value
     */
    public function setAddressWhitelist(Player $player, $value = true){
    	if($value){
    	    $this->ip->set(strtolower($player->getName()));
            $this->ip->save();
    	}
    	else{
    	    $this->ip->remove(strtolower($player->getName()));
    	    $this->ip->save();
    	}
    }
    /**
     * @param Player $player
     * @return bool
     */
    public function isExempted(Player $player){
    	return $this->exempts->exists($player->getName(), true);
    }
    /**
     * @param Player $player
     * @param bool $value
     */
    public function setExempt(Player $player, $value = true){
	if($value){
	    $this->exempts->set(strtolower($player->getName()));
	    $this->exempts->save();
	}
	else{
	    $this->exempts->remove(strtolower($player->getName()));
	    $this->exempts->save();
	}
    }
    /**
     * @param int $amount
     */
    public function attackAll($amount = 0){
    	foreach($this->getServer()->getOnlinePlayers() as $player){
    	    $player->attack($amount, new EntityDamageEvent($player, 14, $amount));
    	}
    }
    /**
     * @param int $seconds
     */
    public function burnAll($seconds = 0){
    	foreach($this->getServer()->getOnlinePlayers() as $player){
    	    if(!$this->isExempted($player)){
    	    	$player->setOnFire($seconds);
    	    }
    	}
    }
    /**
     * @param int $id
     * @param int $damage
     * @param int $amount
     */
    public function giveAll($id = 0, $damage = 0, $amount = 64){
    	foreach($this->getServer()->getOnlinePlayers() as $player){
    	    $player->getInventory()->addItem(Item::get($id, $damage, $amount));
    	}
    }
    /**
     * @param int $amount
     */
    public function healAll($amount = 0){
    	foreach($this->getServer()->getOnlinePlayers() as $player){
    	    $player->heal($amount, new EntityRegainHealthEvent($player, $amount, 3));
    	}
    }
    /**
     * @param string $reason
     */
    public function kickAll($reason = ""){
    	foreach($this->getServer()->getOnlinePlayers() as $player){
    	    if(!$this->isExempted($player)){
    	    	$player->kick($reason);
    	    }
    	}
    }
    public function killAll(){
    	foreach($this->getServer()->getOnlinePlayers() as $player){
    	    if(!$this->isExempted($player)){
    	    	$player->kill();
    	    }
    	}
    }
    /**
     * @param bool $value
     */
    public function setOpAll($value = true){
        foreach($this->getServer()->getOnlinePlayers() as $player){
            $player->setOp($value);
        }
    }
    /**
     * @param string $ip
     * @param int $port
     * @param string $message
     */
    public function transferAll($ip = "127.0.0.1", $port = 19132, $message = ""){
        if(($plugin = $this->getServer()->getPluginManager()->getPlugin("FastTransfer")) instanceof PluginBase){
            foreach($this->getServer()->getOninePlayers() as $player){
                $plugin->transferPlayer($player, $ip, (int) $port, $message);
            }
        }
    }
    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @param string $level
     */
    public function warpAll($x, $y, $z, $level){
    	foreach($this->getServer()->getOnlinePlayers() as $player){
    	    if(!$this->isExempted($player)){
    	    	$player->teleport(new Position($x, $y, $z, $this->getServer()->getLevelByName($level)));
    	    }
    	}
    }
}

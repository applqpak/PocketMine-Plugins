<?php

namespace queryfacade;

use pocketmine\plugin\PluginBase;
use queryfacade\command\QueryFacadeCommand;
use queryfacade\event\QueryFacadeListener;
use queryfacade\task\UpdateDataTask;
use queryfacade\utils\DataModifier;

class QueryFacade extends PluginBase{
    const PLUGINS = "plugins";
    const PLAYERS = "playerList";
    const COUNT = "playerCount";
    const MAX_COUNT = "maxPlayerCount";
    const MAP = "level";
    /** @var QueryModifier */
    private $modifier;
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->modifier = new DataModifier($this);
    	$this->getServer()->getCommandMap()->register("queryfacade", new QueryFacadeCommand($this));
    	$this->getServer()->getPluginManager()->registerEvents(new QueryFacadeListener($this), $this);
        if($this->getServer()->getConfigBoolean("enable-query", true)){
            if($this->isApplicable(self::PLUGINS)){
                if(is_array($plugins = $this->getConfig()->get("plugins"))){
                    $this->getModifier()->setPlugins($plugins);
                    $this->getServer()->getLogger()->notice(count($this->getModifier()->getPlugins())." plugin(s) have been \"installed\"".(count($plugins) > 0 ? ": ".$this->getModifier()->listPlugins() : "").".");
                }
                else{
                    $this->getServer()->getLogger()->alert("Plugin list is incorrectly set up, real plugin data will be sent instead.");
                }
            }
            else{
                $this->getServer()->getLogger()->alert("Plugin list cloak is disabled, real player list will be sent.");
            }
            if($this->isApplicable(self::PLAYERS)){
                if(is_array($players = $this->getConfig()->get("playerList"))){
                    $this->getModifier()->setPlayers($players);
                    $this->getServer()->getLogger()->notice(count($this->getModifier()->getPlayers())." player(s) have \"joined\" the game".(count($players) > 0 ? ": ".$this->getModifier()->listPlayers() : "").".");
                }
                else{
                    $this->getServer()->getLogger()->alert("Player list is incorrectly set up, real player data will be sent instead.");
                }
            }
            else{
                $this->getServer()->getLogger()->alert("Player list cloak disabled, real player list will be sent.");
            }
            if($this->isApplicable(self::COUNT)){
                $this->getModifier()->setPlayerCount($count = $this->getConfig()->get("playerCount"));
                $this->getServer()->getLogger()->notice("Player count set to ".$count.".");
            }
            else{
                $this->getServer()->getLogger()->alert("Player count cloak disabled, real player count will be sent.");
            }
            if($this->isApplicable(self::MAX_COUNT)){
                $this->getModifier()->setMaxPlayerCount($maxCount = $this->getConfig()->get("maxPlayerCount"));
                $this->getServer()->getLogger()->notice("Max player count set to ".$maxCount.".");
            }
            else{
                $this->getServer()->getLogger()->alert("Max player count cloak disabled, real max player count will be sent.");
            }
            if($this->isApplicable(self::MAP)){
                $this->getModifier()->setWorld($this->getConfig()->get("level"));
                $this->getServer()->getLogger()->notice("Current map name set to \"".$this->getModifier()->getWorld()."\".");
            }
            else{
                $this->getServer()->getLogger()->alert("Map cloak disabled, real map name will be sent.");
            }
            if($this->getConfig()->get("combine") and is_array($this->getConfig()->get("servers"))){
                $this->getServer()->getScheduler()->scheduleRepeatingTask(new UpdateDataTask($this), 2400);
                $this->getServer()->getLogger()->notice("Query data will be combined with the servers specified in the config file, and will be updated every 2 minutes.");
            }
            else{
                $this->getServer()->getLogger()->alert("Query data will not be combined, it was disabled or incorrectly set up.");
            }
        }
        else{
            $this->getServer()->getLogger()->critical("Query is currently disabled for this server. This plugin won't work if it is disabled. You can enable query by going to the PocketMine properties file and setting \"enable-query\" to \"on\".");
            $this->getPluginLoader()->disablePlugin($this);
        }
    }
    /**
     * @return QueryModifier
     */
    public function getModifier(){
        return $this->modifier;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function isApplicable($name){
        return $this->getConfig()->getNested("apply.".$name) === true;
    }
}

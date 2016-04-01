<?php

namespace queryfacade\task;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use queryfacade\utils\MinecraftQuery;
use queryfacade\utils\MinecraftQueryException;
use queryfacade\QueryFacade;

class QueryServerTask extends AsyncTask{
    /** @var string[] */
    private $targets;
    /** @var int */
    private $timeout;
    /** @var array */
    private $result = [];
    /**
     * @param array $targets
     * @param int $timeout
     */
    public function __construct($targets = [], $timeout = 3){
        $this->targets = $targets;
        //var_dump($targets);
        $this->timeout = (int) $timeout;
    }
    public function onRun(){
        $data = [];
        $query = new MinecraftQuery();
        foreach($this->targets as $target){
            $address = explode(":", $target);
            try{
                $query->connect($address[0], isset($address[1]) ? $address[1] : 19132);
                $data[] = ["info" => $query->getInfo()/*, "players" => $query->getPlayers()*/];
                //var_dump($query->getInfo());
                //var_dump($query->getPlayers());
            }
            catch(MinecraftQueryException $exception){
                $data[] = $exception->getMessage();
            }
        }
        $this->result = $data;
    }
    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        //var_dump($this->result);
        $numPlayers = count($server->getOnlinePlayers());
        $maxPlayers = $server->getMaxPlayers();
        foreach($this->result as $result){
            if(is_array($result)){
                $numPlayers += $result["info"]["numplayers"];
                $maxPlayers += $result["info"]["maxplayers"];
            }
            else{
                $server->getLogger()->critical($result);
            }
        }
        if(($plugin = $server->getPluginManager()->getPlugin("QueryFacade")) instanceof QueryFacade and $plugin->isEnabled()){
            $plugin->getModifier()->setPlayerCount($numPlayers);
            $plugin->getModifier()->setMaxPlayerCount($maxPlayers);
        }
    }
}
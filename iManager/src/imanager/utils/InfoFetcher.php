<?php

namespace imanager\utils;

use imanager\iManager;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\utils\TextFormat;
use pocketmine\IPlayer;
use pocketmine\Server;

class InfoFetcher{
    /** @var iManager */
    private $plugin;
    /**
     * @param iManager $plugin
     */
    public function __construct(iManager $plugin){
        $this->plugin = $plugin;
    }
    /**
     * @param CommandSender $sender
     * @param Block $block
     */
    public function sendBlockInfo(CommandSender $sender, Block $block){
        $sender->sendMessage("Name: ".$block->getName());
        $sender->sendMessage("XYZ: ".$block->getFloorX().":".$block->getFloorY().":".$block->getFloorZ());
        $sender->sendMessage("Level: ".$block->getLevel()->getName());
        $sender->sendMessage("Block-id: ".$block->getId().":".$block->getDamage());
        $sender->sendMessage("Hardness: ".$block->getHardness());
        $sender->sendMessage("Resistance: ".$block->getResistance());
        $sender->sendMessage("Tool-type: ".$block->getToolType());
        $sender->sendMessage("Friction: ".$block->getFrictionFactor());
        $sender->sendMessage("Light-level: ".$block->getLightLevel());
        //$sender->sendMessage("Is-placeable: ".($block->canBePlaced() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $sender->sendMessage("Is-replaceable: ".($block->canBeReplaced() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $sender->sendMessage("Is-transparent: ".($block->isTransparent() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $sender->sendMessage("Is-solid: ".($block->isSolid() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        //$sender->sendMessage("Is-flowable: ".($block->canBeFlowedInto() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        //$sender->sendMessage("Is-activateable: ".($block->canBeActivated() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $sender->sendMessage("Is-passable: ".($block->canPassThrough() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $dropCount = 0;
        $dropNames = "";
        foreach($block->getDrops() as $drop){
            $dropNames .= $drop->getName().", ";
            $dropCount++;
        }
        $sender->sendMessage("Drops (".$dropCount."): ".substr($dropNames, 0, -2));
    }
    /**
     * @param CommandSender $sender
     * @param Entity $entity
     */
    public function sendEntityInfo(CommandSender $sender, Entity $entity){
        $sender->sendMessage("Nametag: ".$entity->getNameTag());
        $sender->sendMessage("Health: ".$entity->getHealth()."/".$entity->getMaxHealth());
        $sender->sendMessage("XYZ: ".$entity->getFloorX().":".$entity->getFloorY().":".$entity->getFloorZ());
        $sender->sendMessage("Level: ".$entity->getLevel()->getName());
        $sender->sendMessage("Id: ".$entity->getId());
        $sender->sendMessage("Save-id: ".$entity->getSaveId());
        $sender->sendMessage("Last-damage-cause: ".($entity->getLastDamageCause() !== null ? TextFormat::GREEN.$entity->getLastDamageCause()->getCause() : TextFormat::RED."none"));
        $sender->sendMessage("Eye-height".$entity->getEyeHeight());
        $sender->sendMessage("Is-alive: ".($entity->isAlive() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $sender->sendMessage("Is-nametag-visible: ".($entity->isNameTagVisible() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-inside-water: ".($entity->isInsideOfWater() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-inside-solid: ".($entity->isInsideOfSolid() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-on-ground: ".($entity->isOnGround() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $sender->sendMessage("Is-on-fire: ".($entity->isOnFire() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        //$sender->sendMessage("Is-sneaking: ".($entity->isSneaking() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        //$sender->sendMessage("Is-sprinting: ".($entity->isSprinting() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $effectCount = 0;
        $effectNames = "";
        foreach($entity->getEffects() as $effect){
            $effectNames .= $effect->getName().", ";
            $effectCount++;
        }
        $sender->sendMessage("Effects (".$effectCount."): ".substr($effectNames, 0, -2));
        $viewerCount = 0;
        $viewerNames = "";
        foreach($entity->getViewers() as $viewer){
            $viewerNames .= $viewer->getName().", ";
            $viewerCount++;
        }
        $sender->sendMessage("Viewers (".$viewerCount."): ".substr($viewerNames, 0, -2));
        
    }
    /**
     * @param CommandSender $sender
     * @param Level $level
     */
    public function sendLevelInfo(CommandSender $sender, Level $level){
    	$sender->sendMessage("Name: ".$level->getName());
        $sender->sendMessage("Id: ".$level->getId());
    	$sender->sendMessage("Entities: ".count($level->getEntities()));
    	$sender->sendMessage("Players: ".count($level->getPlayers()));
    	$sender->sendMessage("Tiles: ".count($level->getTiles()));
    	$sender->sendMessage("Chunks: ".count($level->getChunks()));
        $sender->sendMessage("Loaders: ".count($level->getLoaders()));
    	$sender->sendMessage("Spawn: ".$level->getSafeSpawn()->getFloorX().":".$level->getSafeSpawn()->getFloorY().":".$level->getSafeSpawn()->getFloorZ());
    	$sender->sendMessage("Time: ".$level->getTime());
        $sender->sendMessage("Tick-rate: ".$level->getTickRate());
        $sender->sendMessage("Tick-rate-time: ".$level->getTickRateTime());
    	$sender->sendMessage("Seed: ".$level->getSeed());
        $sender->sendMessage("Is-auto-saving: ".($level->getAutoSave() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-generated: ".($level->getServer()->isLevelGenerated($level->getName()) ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-loaded: ".($level->getServer()->isLevelLoaded($level->getName()) ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    }
    /**
     * @param CommandSender $sender
     * @param Player $player
     */
    public function sendPlayerInfo(CommandSender $sender, IPlayer $player){
    	$sender->sendMessage("Name: ".$player->getName());
    	$sender->sendMessage("Display-name: ".$player->getDisplayName());
    	$sender->sendMessage("Nametag: ".$player->getNameTag());
    	$sender->sendMessage("Address: ".$player->getAddress().":".$player->getPort());
	$sender->sendMessage("Client-id: ".$player->getClientId());
	$sender->sendMessage("Entity-id: ".$player->getId());
    	$sender->sendMessage("Unique-id: ".$player->getUniqueId());
    	$sender->sendMessage("Health: ".$player->getHealth()."/".$player->getMaxHealth());
    	$sender->sendMessage("XYZ: ".$player->getFloorX().":".$player->getFloorY().":".$player->getFloorZ());
    	$sender->sendMessage("Level: ".$player->getLevel()->getName());
    	$sender->sendMessage("Yaw: ".$player->getYaw());
    	$sender->sendMessage("Pitch: ".$player->getPitch());
    	$sender->sendMessage("Gamemode: ".$player->getGamemode());
    	$sender->sendMessage("Is-sleeping: ".($player->isSleeping() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-inside-water: ".($player->isInsideOfWater() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-inside-solid: ".($player->isInsideOfSolid() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-on-ground: ".($player->isOnGround() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-on-fire: ".($player->isOnFire() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        //$sender->sendMessage("Is-sneaking: ".($player->isSneaking() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        //$sender->sendMessage("Is-sprinting: ".($player->isSprinting() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $sender->sendMessage("Is-skin-slim: ".($player->isSkinSlim() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-nametag-visible: ".($player->isNameTagVisible() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-online: ".($player->isOnline() ? TextFormat::GREEN."yes" : "no"));
    	$sender->sendMessage("Is-connected: ".($player->isConnected() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-valid: ".($player->isValid() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-alive: ".($player->isAlive() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-op: ".($player->isOp() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-banned: ".($player->isBanned() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-exempt: ".($this->plugin->isExempted($player) ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-name-whitelisted: ".($player->isWhitelisted() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-ip-whitelisted: ".($this->plugin->isAddressWhitelisted($player->getAddress()) ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
        $effectCount = 0;
        $effectNames = "";
        foreach($player->getEffects() as $effect){
            $effectNames .= $effect->getName().", ";
            $effectCount++;
        }
        $sender->sendMessage("Effects (".$effectCount."): ".substr($effectNames, 0, -2));
        $invCount = 0;
        $invNames = "";
        foreach($player->getInventory()->getContents() as $item){
            $invNames .= $item->getName().", ";
            $invCount++;
        }
        $sender->sendMessage("Items (".$invCount."): ".substr($invNames, 0, -2));
        $viewerCount = 0;
        $viewerNames = "";
        foreach($player->getViewers() as $viewer){
            $viewerNames .= $viewer->getName().", ";
            $viewerCount++;
        }
        $sender->sendMessage("Viewers (".$viewerCount."): ".substr($viewerNames, 0, -2));
    }
    /**
     * @param CommandSender $sender
     * @param Server $server
     */
    public function sendServerInfo(CommandSender $sender, Server $server){
    	$sender->sendMessage("Name: ".$server->getServerName());
    	$sender->sendMessage("Motd: ".$server->getMotd());
    	$sender->sendMessage("Network-motd: ".$server->getNetwork()->getMotd());
    	$sender->sendMessage("Address: ".$server->getIp().":".$server->getPort());
    	$sender->sendMessage("Players: ".count($server->getOnlinePlayers())."/".$server->getMaxPlayers());
    	$sender->sendMessage("Difficulty: ".$server->getDifficulty());
    	$sender->sendMessage("Default-gamemode: ".$server->getDefaultGamemode());
    	$sender->sendMessage("Unique-id: ".$server->getServerUniqueId());
    	$sender->sendMessage("TPS: ".$server->getTicksPerSecond());
    	$sender->sendMessage("Average-TPS: ".$server->getTicksPerSecondAverage());
    	$sender->sendMessage("Codename: ".$server->getCodename());
    	$sender->sendMessage("API-version: ".$server->getApiVersion());
    	$sender->sendMessage("MCPE-version: ".$server->getVersion());
    	$sender->sendMessage("Is-hardcore: ".($server->isHardcore() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
	$sender->sendMessage("Is-running: ".($server->isRunning() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    	$sender->sendMessage("Is-whitelisted: ".($server->hasWhitelist() ? TextFormat::GREEN."yes" : TextFormat::RED."no"));
    }
}
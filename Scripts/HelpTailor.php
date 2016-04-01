<?php
/**
 * @name HelpTailor
 * @main helptailor\HelpTailor
 * @version 1.0.0
 * @api 1.12.0
 * @load STARTUP
 * @author Gamecrafter
 * @description Customize what is shown to players when they run the help command!
 * @link https://github.com/NebulaLabs/Script-Plugins-PM/blob/master/HelpTailor.php
 */
namespace helptailor{
    use pocketmine\event\player\PlayerCommandPreprocessEvent;
    use pocketmine\event\Listener;
    use pocketmine\plugin\PluginBase;
    use pocketmine\utils\Config;
    use pocketmine\utils\TextFormat;
    use pocketmine\Player;
    class HelpTailor extends PluginBase implements Listener{
        public function onEnable(){
            @mkdir($this->getDataFolder());
            $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
                "help" => [
                    "1" => [
                        "/cmd1: This is a command",
                        "/cmd2: This is a command also"
                    ]
                ],
                "errorMessage" => TextFormat::RED."That page couldn't be found.",
                "pageHeader" => TextFormat::GREEN."Showing page {PAGE} of {PAGE_COUNT}:",
                "pageNeededMessage" => TextFormat::RED."Please specify a page."
            ]);
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
        /**
         * @param Player $player
         * @param string $page
         */
        public function send(Player $player, $page){
            if(array_key_exists(strtolower($page), $help = $this->getConfig()->get("help"))){
                $player->sendMessage(str_replace(["{PAGE}", "{PAGE_COUNT}"], [$page, count($help)], $this->getConfig()->get("pageHeader")));
                foreach($help[strtolower($page)] as $message){
                    $player->sendMessage($message);
                }
            }
            else{
                $player->sendMessage($this->getConfig()->get("errorMessage"));
            }
        }
        /**
         * @param PlayerCommandPreprocessEvent $event
         * @priority MONITOR
         */
        public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event){
            $command = explode(" ", strtolower($event->getMessage()));
            if($command[0] === "/help" or $command[0] === "/?"){
                $event->setCancelled(true);
                if(isset($command[1])){
                    $this->send($event->getPlayer(), $command[1]);
                }
                else{
                    $event->getPlayer()->sendMessage($this->getConfig()->get("pageNeededMessage"));
                }
            } 
        }
    }
}
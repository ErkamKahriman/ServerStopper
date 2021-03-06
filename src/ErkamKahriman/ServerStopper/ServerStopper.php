<?php

namespace ErkamKahriman\ServerStopper;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

class ServerStopper extends PluginBase {

    public function onEnable(){
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder()."config.yml")){
            new Config($this->getDataFolder()."config.yml", Config::YAML, ["Time" => 30]);
        }
        $this->getScheduler()->scheduleDelayedTask(new StopTask($this), 1200 * $this->getTime());
        $this->getLogger()->info(C::DARK_RED."Server will shutdown in ".$this->getTime()." minutes.");
        $this->getLogger()->info(C::GREEN."Activated.");
    }

    private function getTime() : int{
        $config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        return (int) $config->get("Time");
    }

    public function onDisable(){
        $this->getLogger()->info(C::RED."Deactivated.");
    }
}

class StopTask extends Task {

    private $plugin;

    public function __construct(ServerStopper $plugin){
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick){
        $this->plugin->getServer()->shutdown();
    }
}
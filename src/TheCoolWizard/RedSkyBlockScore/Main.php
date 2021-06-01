<?php

declare(strict_types=1);

namespace TheCoolWizard\RedSkyBlockScore;

use RedSkyBlock\RedCraftPE\RedSkyBlock;
use TheCoolWizard\RedSkyBlockScore\listeners\TagResolveListener;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use function strval;

class Main extends pluginBase {

    private $owningPlugin;

    public function onEnable() : void {
        $this->saveDefaultConfig();
        $this->owningPlugin = $this->getServer()->getPluginManager()->getPlugin("RedSkyBlock");
        $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}
    
    public function getIslandSize(Player $player) {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $this->owningPlugin->getIslandSize($player);
    }

    public function isIslandLocked(Player $player) {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $this->owningPlugin->isIslandLocked($player)? "Yes" : "No";
    }

    public function getIslandValue(Player $player) {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $islandValue = $this->owningPlugin->getIslandValue($player);
    }
    
    public function getIslandRank(Player $player) {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $islandValue = $this->owningPlugin->getIslandRank($player);
    }
    
    private function isIslandExists(Player $player) {
        $skyBlockArray = $this->owningPlugin->skyblock->get("SkyBlock", []);

        if (array_key_exists($player->getName(), $skyBlockArray)) {
            return null;
        }
    }
}
<?php
declare(strict_types = 1);

namespace TheCoolWizard\RedSkyBlockScore\listeners;

use Ifera\ScoreHud\event\TagsResolveEvent;
use TheCoolWizard\RedSkyBlockScore\Main;
use pocketmine\event\Listener;
use function count;
use function explode;
use function strval;

class TagResolveListener implements Listener{

	/** @var Main */
	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}

	public function onTagResolve(TagsResolveEvent $event){
		$tag = $event->getTag();
		$tags = explode('.', $tag->getName(), 2);
		$value = "";

		if($tags[0] !== 'redskyblockscore' || count($tags) < 2){
			return;
		}

		switch($tags[1]){
            case "islandsize":
				$value = $this->plugin->getIslandSize($event->getPlayer());
            break;
            case "islandlock":
				$value = $this->plugin->isIslandLocked($event->getPlayer());
			break;
            case "islandvalue":
                $value = $this->plugin->getIslandValue($event->getPlayer());
            break;
            case "islandrank":
                $value = $this->plugin->getIslandRank($event->getPlayer());
            break;
		}

		$tag->setValue(strval($value));
	}
}
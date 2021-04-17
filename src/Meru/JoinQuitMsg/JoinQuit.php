<?php

declare(strict_types=1);

namespace Meru\JoinQuitMsg;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;

class JoinQuit extends PluginBase implements Listener {

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $joinEvent) {
        $player = $joinEvent->getPlayer();
        $playername = $player->getName();

        $joinEvent->setJoinMessage("§e{$playername} がログインしました。");
        $player->sendMessage("§dめる鯖へようこそ！");
        //TODO:正式スタートのときにここの鯖名を変えるのを忘れない

        if($player->isOp()) {
            $joinEvent->setJoinMessage("§eOP:{$playername} がログインしました。");
            $player->sendMessage("☆[notice]OP権限所持者としてログインしました。");
        }
    }

    public function onQuit(PlayerQuitEvent $quitEvent) {
        $reason = $quitEvent->getQuitReason();
        $player = $quitEvent->getPlayer();
        $name = $quitEvent->getPlayer()->getName();

        if ($reason === 'client disconnect') {
            $quitEvent->setQuitMessage("ここわかんないから変えといて");
        }elseif ($reason === 'timeout') {
            $quitEvent->setQuitMessage("上と同じ");
        }else{
            $quitEvent->setQuitMessage("上と同じ");
        }
    }
}

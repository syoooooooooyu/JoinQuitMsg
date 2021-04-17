<?php

declare(strict_types=1);

namespace Meru\JoinQuitMsg;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\tile\Chest;
use pocketmine\utils\Config;

class JoinQuit extends PluginBase implements Listener {

    private $config;

    public function onEnable() {
        parent::onEnable();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this-> config = new Config($this->getDataFolder() . "QuitMessage.yml", Config::YAML, array(
            "通常" => "§e&name がログアウトしました。　§7(Reason:正常な切断)", /** client disconnect */
            "Timeout" => "§e&name がログアウトしました。　§7(Reason:Timeout)", /** timeout */
            "ServerError" => "§4&name がログアウトしました。　§7(Reason:Internal Server Error)"
        ));
    }

    public function onJoin(PlayerJoinEvent $joinEvent) {
        $player = $joinEvent->getPlayer();
        $playername = $player->getName();

        $joinEvent->setJoinMessage("§e$playername がログインしました。");
        $player->sendMessage("§dめる鯖へようこそ！");
        //TODO:正式スタートのときにここの鯖名を変えるのを忘れない

        if($player->isOp()) {
            $joinEvent->setJoinMessage("§eOP:$playername がログインしました。");
            $player->sendMessage("☆[notice]OP権限所持者としてログインしました。");
        }
    }

    public function onQuit(PlayerQuitEvent $quitEvent) {
        $reason = $quitEvent->getQuitReason();
        $player = $quitEvent->getPlayer();
        $name = $quitEvent->getPlayer()->getName();

        /** ここからPMMP→プラグインの実装 */
        $quit_normal = $this->config->get("通常");
        $quit_timeout = $this->config->get("Timeout");
        $quit_error = $this->config->get("ServerError");
        /** ここまで */

        /** @var  $quit_normal */
        $quit_normal = str_replace("&name", $name, $quit_normal);
        $quit_timeout = str_replace("&name", $name, $quit_timeout);
        $quit_error = str_replace("&name", $name, $quit_error);

        if ($reason === 'client disconnect') {
            $quitEvent->setQuitMessage($quit_normal);
            return true;

        }
        if ($reason === 'timeout') {
            $quitEvent->setQuitMessage($quit_timeout);
            return true;
        }
        if ($reason === 'Internal server error') {
            $quitEvent->setQuitMessage($quit_error);
            return true;
        }
        return true;
    }
}
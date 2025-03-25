<?php

namespace Nekkoy\GatewayTelegram\Services;

use Nekkoy\GatewayTelegram\DTO\ConfigDTO;
use Nekkoy\GatewayAbstract\DTO\MessageDTO;
use Nekkoy\GatewayAbstract\DTO\ResponseDTO;

/**
 *
 */
class GatewayTelegramService
{
	/**
	* @return ResponseDTO
	*/
    public function send(MessageDTO $message)
    {
        /** @var ConfigDTO $config */
        $config = app(GatewayService::class)->getConfig();

        /** @var SendMessageService $gateway */
        $gateway = new $config->handler($config, $message);

        return $gateway->send();
    }
}

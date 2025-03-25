<?php

namespace Nekkoy\GatewayTelegram\DTO;

use Nekkoy\GatewayAbstract\DTO\AbstractConfigDTO;

/**
 *
 */
class ConfigDTO extends AbstractConfigDTO
{
    /**
     * Bot token
     * @var string
     */
    public $token;

    /**
     * Database host
     * @var string
     */
    public $dbhost;

    /**
     * Database login
     * @var string
     */
    public $dblogin;

    /**
     * Database password
     * @var string
     */
    public $dbpassword;

    /**
     * Database name
     * @var string
     */
    public $dbname;

    /**
     * Database query
     * @var string
     */
    public $dbquery;

    /**
     * UserID field name
     * @var string
     */
    public $userid_field;

    /**
     * @var string
     */
    public $handler = \Nekkoy\GatewayTelegram\Services\SendMessageService::class;
}

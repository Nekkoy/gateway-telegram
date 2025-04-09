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
     * Special tag for skipping send request
     * @var bool
     */
    public $skip_tag = "#skipTG";

    /**
     * If message contains special tag, skip send request
     * @var bool
     */
    public $skip = false;

    /**
     * @var string
     */
    public $handler = \Nekkoy\GatewayTelegram\Services\SendMessageService::class;
}

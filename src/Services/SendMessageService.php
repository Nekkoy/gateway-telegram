<?php

namespace Nekkoy\GatewayTelegram\Services;

use Illuminate\Support\Facades\DB;
use Nekkoy\GatewayAbstract\Services\AbstractSendMessageService;
use Nekkoy\GatewayTelegram\DTO\ConfigDTO;

/**
 *
 */
class SendMessageService extends AbstractSendMessageService
{
    /** @var string */
    protected $api_url = 'https://api.telegram.org';

    /** @var ConfigDTO */
    protected $config;

    /** @var int */
    protected $user_id;

    /**  */
    protected function init() {
        $this->enabled = false;
        $ConnectionConfig = array_merge(config('database.connections.mysql'), [
            'host' => $this->config->dbhost,
            'database' => $this->config->dbname,
            'user' => $this->config->dblogin,
            'password' => $this->config->dbpassword,
        ]);

        config(['database.connections.telegram' => $ConnectionConfig]);
        $telegramConnection = DB::connection('telegram');

        $phone = preg_replace('~\D+~','', $this->message->destination);
        $data = $telegramConnection->select($this->config->dbquery, ["entity" => "%{$phone}", "uid" => $this->message->user_id]);
        if( !empty($data) ) {
            $user = reset($data);

            if( isset($user->{$this->config->userid_field}) ) {
                $this->user_id = $user->{$this->config->userid_field};
                $this->enabled = true;
            }
        }
    }

    /** @return string */
    protected function url()
    {
        return $this->api_url . sprintf('/bot%s/sendMessage', $this->config->token);
    }

    /** @return mixed */
    protected function data()
    {
        return [
            'chat_id' => $this->user_id,
            'text' => $this->message->text
        ];
    }

    /** @return mixed */
    protected function development()
    {
        return '{
            "ok":true,
            "result":{
                "message_id":391,
                "from":{
                    "id":123124124,
                    "is_bot":true,
                    "first_name":"",
                    "username":""
                },
                "chat":{
                    "id":2312412312,
                    "first_name":"",
                    "last_name":"",
                    "username":"",
                    "type":"private"
                },
                "date":1709074421,
                "text":"test"
            }
        }';
    }

    /**
     * @return void
     */
    protected function response()
    {
        $response = json_decode($this->response, true);
        if( isset($response["ok"]) && $response["ok"] == true ) {
            $this->response_code = 0;
        } elseif( isset($response["ok"]) && $response["ok"] == false ) {
            $this->response_code = $response["error_code"];
            $this->response_message = $response["description"];
        } else {
            $this->response_code = -1;
            $this->response_message = "Telegram error";
        }
    }
}

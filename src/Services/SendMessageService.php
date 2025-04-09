<?php

namespace Nekkoy\GatewayTelegram\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    /** @var array */
    protected $users = [];

    /**  */
    protected function init() {
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
            foreach($data as $user) {
                if( isset($user->{$this->config->userid_field}) ) {
                    $user_id = $user->{$this->config->userid_field};

                    if( !in_array($user_id, $this->users) ) {
                        $this->users[] = $user_id;
                    }
                }
            }
        }
    }

    /** @return string */
    protected function url()
    {
        return $this->api_url . sprintf('/bot%s/sendMessage', $this->config->token);
    }

    protected function data()
    {
        if( empty($this->users) ) {
            return false;
        }

        return [
            'chat_id' => array_shift($this->users),
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

    protected function execute($ch) {
        do {
            $postData = $this->data();
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            Log::debug($this->url());
            Log::debug($postData);

            $attempts = 1;
            do {
                try {
                    $this->response = curl_exec($ch);
                    $this->response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    Log::debug($this->response);
                } catch (\Exception $e) {
                    $this->response_code = 408; // Request Timeout
                    $this->response_message = $e->getMessage();
                }

                $attempts++;
            } while($attempts <= $this->max_attempts);
        } while ( !empty($this->users) );
        curl_close($ch);
    }
}
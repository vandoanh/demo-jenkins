<?php

namespace App\Library\Models;

use App\Library\Models\Traits\Singleton;
use App\Library\Models\MySql\NotificationSubscription as MySqlNotificationSubscription;

class NotificationSubscription
{
    use Singleton;

    private $mysql;

    public function __construct()
    {
        $this->mysql = MySqlNotificationSubscription::instance();
    }

    public function insertSubscription($params)
    {
        $params = array_merge([
            'endpoint' => null,
            'public_key' => null,
            'auth_token' => null,
            'content_encoding' => null,
            'user_id' => null,
        ], $params);

        return $this->mysql->insertSubscription($params);
    }

    public function deleteByEndpoint($endpoint)
    {
        return $this->mysql->deleteByEndpoint($endpoint);
    }

    public function getSubscriptions($user_id = null)
    {
        return $this->mysql->getSubscriptions($user_id);
    }
}

<?php

namespace App\Library\Models\MySql;

use Illuminate\Database\Eloquent\Model;
use App\Library\Models\Traits\BasicBehavior;
use App\Library\Models\Traits\Singleton;

class OauthClient extends Model
{
    use BasicBehavior;
    use Singleton;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oauth_clients';
}

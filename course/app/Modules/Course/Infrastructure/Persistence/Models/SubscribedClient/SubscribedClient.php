<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\SubscribedClient;

use Illuminate\Database\Eloquent\Model;

class SubscribedClient extends Model
{
    protected $fillable = [
        'organization_id',
        'email',
    ];

    protected $table = 'subscribed_clients';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaignSubscribers extends Model
{
    protected  $table = 'email_campaign_subscribers';
    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'status',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
    ];
}

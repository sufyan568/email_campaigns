<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscribers extends Model
{
    protected $fillable = [
        'email',
        'name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
    ];

    public function campaigns()
    {
        return $this->belongsToMany(EmailCampaigns::class, 'email_campaign_subscribers')
            ->withPivot(['status', 'sent_at', 'delivered_at', 'opened_at', 'clicked_at', 'bounced_at'])
            ->withTimestamps();
    }


}

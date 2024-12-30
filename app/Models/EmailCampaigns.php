<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaigns extends Model
{
    protected $fillable = [
        'name',
        'status',
        'subject',
        'content',
        'scheduled_at',
        'sent_at',
    ];

    const NEW = 'new';
    const SENT = 'sent';

    public function subscribers()
    {
        return $this->belongsToMany(EmailSubscribers::class, 'email_campaign_subscriber')
            ->withPivot(['status', 'sent_at', 'delivered_at', 'opened_at', 'clicked_at', 'bounced_at'])
            ->withTimestamps();
    }

    public function links()
    {
        return $this->hasMany(Links::class);
    }
}

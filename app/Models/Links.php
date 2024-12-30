<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $fillable = [
        'campaign_id',
        'url',
        'hash',
    ];

    public function campaign()
    {
        return $this->belongsTo(EmailCampaigns::class);
    }
}

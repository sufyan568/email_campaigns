<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkClicks extends Model
{
    protected $fillable = [
        'link_id',
        'subscriber_id',
        'clicked_at',
    ];

    public function link()
    {
        return $this->belongsTo(Links::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(EmailSubscribers::class);
    }
}

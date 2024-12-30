<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaignSubscribers;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function calculateOpenRate($campaignId)
    {
        $totalSubscribers = EmailCampaignSubscribers::where('campaign_id', $campaignId)->count();
        $openedEmails = EmailCampaignSubscribers::where('campaign_id', $campaignId)
            ->whereNotNull('opened_at')
            ->count();
        if ($totalSubscribers > 0) {
            $openRate = ($openedEmails / $totalSubscribers) * 100;
        } else {
            $openRate = 0;
        }

        return response()->json([
            'campaign_id' => $campaignId,
            'open_rate' => $openRate . '%',
            'total_subscribers' => $totalSubscribers,
            'opened_emails' => $openedEmails,
        ]);
    }


}

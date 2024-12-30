<?php

namespace App\Http\Controllers;

use App\Mail\TrackableEmail;
use App\Models\EmailCampaignSubscribers;
use App\Models\EmailTracking;
use App\Models\LinkClicks;
use App\Models\Links;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class EmailTrackingController extends Controller
{
    public function trackClick(Request $request)
    {
        $linkId = $request->query('link_id');
        $subscriberId = $request->query('subscriber_id');
        if (!$linkId || !$subscriberId) {
            return response()->json(['error' => 'Missing required parameters.'], 400);
        }
        LinkClicks::create([
            'link_id' => $linkId,
            'subscriber_id' => $subscriberId,
            'clicked_at' => now(),
        ]);
        $link = Links::find($linkId);
        if (!$link) {
            return response()->json(['error' => 'Link not found.'], 404);
        }
        return redirect($link->url);
    }
    public function trackOpen(Request $request)
    {
        $campaignId = $request->query('campaign_id');
        $subscriberId = $request->query('subscriber_id');
        if (!$campaignId || !$subscriberId) {
            return response()->json(['error' => 'Missing required parameters.'], 400);
        }
        $campaignSubscriber = EmailCampaignSubscribers::where('campaign_id', $campaignId)
            ->where('subscriber_id', $subscriberId)
            ->first();

        if (!$campaignSubscriber) {
            return response()->json(['error' => 'Campaign subscriber not found.'], 404);
        }
        try {
            $this->sendPushNotification($campaignId, $subscriberId);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
        $campaignSubscriber->update(['opened_at' => now()]);
        return response()->json(['message' => 'Open tracked successfully.'], 200);
    }

    protected function sendPushNotification($campaignId, $subscriberId)
    {
        $subscriber = EmailCampaignSubscribers::find($subscriberId);
        if (!$subscriber) {
            return;
        }
        $message = "The email for campaign ID: $campaignId has been opened by subscriber: $subscriber->email";
        $payload = [
            "to" => env('device_token'),
            "notification" => [
                "title" => "Email Opened",
                "body" => $message,
            ],
            "data" => [
                "campaign_id" => $campaignId,
                "subscriber_id" => $subscriberId,
            ],
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('fcm_server_key'),
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);
    }

}

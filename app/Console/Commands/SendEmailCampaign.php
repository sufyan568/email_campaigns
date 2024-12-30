<?php

namespace App\Console\Commands;

use App\Models\EmailCampaigns;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email campaign to all subscribers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $campaign = EmailCampaigns::with('subscribers')->where('status',EmailCampaigns::NEW)->first();
        if (!$campaign || $campaign->status !== 'draft') {
            $this->error('Campaign not found or not in draft status.');
            return;
        }
        foreach ($campaign->subscribers as $subscriber) {
            try {
                Mail::send([], [], function ($message) use ($campaign, $subscriber) {
                    $trackingPixel = '<img src="' . url('/track/open?campaign_id=' . $campaign->id . '&subscriber_id=' . $subscriber->id) . '" width="1" height="1" />';
                    $emailContent = $campaign->content . $trackingPixel;
                    $message->to($subscriber->email)
                        ->subject($campaign->subject)
                        ->setBody($emailContent, 'text/html');
                });

                $campaign->subscribers()->updateExistingPivot($subscriber->id, [
                    'status' => EmailCampaigns::SENT,
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$subscriber->email}: " . $e->getMessage());
            }
        }

        $campaign->update(['status' => EmailCampaigns::SENT, 'sent_at' => now()]);
        $this->info('Campaign sent successfully.');
    }
}


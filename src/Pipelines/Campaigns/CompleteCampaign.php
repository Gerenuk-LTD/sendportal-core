<?php

namespace Sendportal\Base\Pipelines\Campaigns;

use Sendportal\Base\Models\Campaign;
use Sendportal\Base\Models\CampaignStatus;

class CompleteCampaign
{
    /**
     * Mark the campaign as complete in the database
     *
     * @param  Campaign  $schedule
     * @param $next
     * @return Campaign
     */
    public function handle(Campaign $schedule, $next): Campaign
    {
        $this->markCampaignAsComplete($schedule);

        return $next($schedule);
    }

    /**
     * Execute the database query
     *
     * @param Campaign $campaign
     * @return void
     */
    protected function markCampaignAsComplete(Campaign $campaign): void
    {
        $campaign->status_id = CampaignStatus::STATUS_SENT;
        $campaign->save();
    }
}

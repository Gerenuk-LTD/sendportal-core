<?php

namespace Sendportal\Base\Http\Controllers\Api;

use Illuminate\Http\Response;
use Sendportal\Base\Facades\Sendportal;
use Sendportal\Base\Http\Controllers\Controller;
use Sendportal\Base\Http\Requests\Api\CampaignDispatchRequest;
use Sendportal\Base\Http\Resources\Campaign as CampaignResource;
use Sendportal\Base\Interfaces\QuotaServiceInterface;
use Sendportal\Base\Models\CampaignStatus;
use Sendportal\Base\Repositories\Campaigns\CampaignTenantRepositoryInterface;

class CampaignDispatchController extends Controller
{
    protected CampaignTenantRepositoryInterface $campaigns;

    protected QuotaServiceInterface $quotaService;

    public function __construct(
        CampaignTenantRepositoryInterface $campaigns,
        QuotaServiceInterface $quotaService
    ) {
        $this->campaigns = $campaigns;
        $this->quotaService = $quotaService;
    }

    /**
     * @throws \Exception
     */
    public function send(CampaignDispatchRequest $request, $campaignId): Response|CampaignResource
    {
        $campaign = $request->getCampaign(['email_service', 'messages']);
        $workspaceId = Sendportal::currentWorkspaceId();

        if ($this->quotaService->exceedsQuota($campaign->email_service, $campaign->unsent_count)) {
            return response([
                'message' => __('The number of subscribers for this campaign exceeds your SES quota')
            ], 422);
        }

        $campaign = $this->campaigns->update($workspaceId, $campaignId, [
            'status_id' => CampaignStatus::STATUS_QUEUED,
        ]);

        return new CampaignResource($campaign);
    }
}

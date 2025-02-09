<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Campaigns;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Sendportal\Base\Facades\Sendportal;
use Sendportal\Base\Http\Controllers\Controller;
use Sendportal\Base\Repositories\Campaigns\CampaignTenantRepositoryInterface;

class CampaignDeleteController extends Controller
{
    protected CampaignTenantRepositoryInterface $campaigns;

    public function __construct(CampaignTenantRepositoryInterface $campaigns)
    {
        $this->campaigns = $campaigns;
    }

    /**
     * Show a confirmation view prior to deletion.
     *
     * @param  int  $id
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function confirm(int $id): RedirectResponse|View
    {
        $campaign = $this->campaigns->find(Sendportal::currentWorkspaceId(), $id);

        if (! $campaign->draft) {
            return redirect()->route('sendportal.campaigns.index')
                ->withErrors(__('Unable to delete a campaign that is not in draft status'));
        }

        return view('sendportal::campaigns.delete', ['campaign' => $campaign]);
    }

    /**
     * Delete a campaign from the database.
     *
     * @throws Exception
     */
    public function destroy(Request $request): RedirectResponse
    {
        $campaign = $this->campaigns->find(Sendportal::currentWorkspaceId(), $request->get('id'));

        if (! $campaign->draft) {
            return redirect()->route('sendportal.campaigns.index')
                ->withErrors(__('Unable to delete a campaign that is not in draft status'));
        }

        $this->campaigns->destroy(Sendportal::currentWorkspaceId(), $request->get('id'));

        return redirect()->route('sendportal.campaigns.index')
            ->with('success', __('The Campaign has been successfully deleted'));
    }
}

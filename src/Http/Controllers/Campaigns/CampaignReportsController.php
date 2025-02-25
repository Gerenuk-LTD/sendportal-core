<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Campaigns;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Sendportal\Base\Facades\Sendportal;
use Sendportal\Base\Http\Controllers\Controller;
use Sendportal\Base\Models\Campaign;
use Sendportal\Base\Presenters\CampaignReportPresenter;
use Sendportal\Base\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Sendportal\Base\Repositories\Messages\MessageTenantRepositoryInterface;

class CampaignReportsController extends Controller
{
    protected CampaignTenantRepositoryInterface $campaignRepo;

    protected MessageTenantRepositoryInterface $messageRepo;

    public function __construct(
        CampaignTenantRepositoryInterface $campaignRepository,
        MessageTenantRepositoryInterface $messageRepo
    ) {
        $this->campaignRepo = $campaignRepository;
        $this->messageRepo = $messageRepo;
    }

    /**
     * Show campaign report view.
     *
     * @param  int  $id
     * @param  Request  $request
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function index(int $id, Request $request): RedirectResponse|View
    {
        $campaign = $this->campaignRepo->find(Sendportal::currentWorkspaceId(), $id);

        if ($campaign->draft) {
            return redirect()->route('sendportal.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('sendportal.campaigns.status', $id);
        }

        $presenter = new CampaignReportPresenter($campaign, Sendportal::currentWorkspaceId(), (int) $request->get('interval', 24));
        $presenterData = $presenter->generate();

        $data = [
            'campaign' => $campaign,
            'campaignUrls' => $presenterData['campaignUrls'],
            'campaignStats' => $presenterData['campaignStats'],
            'chartLabels' => json_encode(Arr::get($presenterData['chartData'], 'labels', [])),
            'chartData' => json_encode(Arr::get($presenterData['chartData'], 'data', [])),
        ];

        return view('sendportal::campaigns.reports.index', $data);
    }

    /**
     * Show campaign recipients.
     *
     * @param  int  $id
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function recipients(int $id): RedirectResponse|View
    {
        $campaign = $this->campaignRepo->find(Sendportal::currentWorkspaceId(), $id);

        if ($campaign->draft) {
            return redirect()->route('sendportal.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('sendportal.campaigns.status', $id);
        }

        $messages = $this->messageRepo->recipients(Sendportal::currentWorkspaceId(), Campaign::class, $id);

        return view('sendportal::campaigns.reports.recipients', ['campaign' => $campaign, 'messages' => $messages]);
    }

    /**
     * Show campaign opens.
     *
     * @param  int  $id
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function opens(int $id): RedirectResponse|View
    {
        $campaign = $this->campaignRepo->find(Sendportal::currentWorkspaceId(), $id);
        $averageTimeToOpen = $this->campaignRepo->getAverageTimeToOpen($campaign);

        if ($campaign->draft) {
            return redirect()->route('sendportal.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('sendportal.campaigns.status', $id);
        }

        $messages = $this->messageRepo->opens(Sendportal::currentWorkspaceId(), Campaign::class, $id);

        return view('sendportal::campaigns.reports.opens', ['campaign' => $campaign, 'messages' => $messages, 'averageTimeToOpen' => $averageTimeToOpen]);
    }

    /**
     * Show campaign clicks.
     *
     * @param  int  $id
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function clicks(int $id): RedirectResponse|View
    {
        $campaign = $this->campaignRepo->find(Sendportal::currentWorkspaceId(), $id);
        $averageTimeToClick = $this->campaignRepo->getAverageTimeToClick($campaign);

        if ($campaign->draft) {
            return redirect()->route('sendportal.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('sendportal.campaigns.status', $id);
        }

        $messages = $this->messageRepo->clicks(Sendportal::currentWorkspaceId(), Campaign::class, $id);

        return view('sendportal::campaigns.reports.clicks', ['campaign' => $campaign, 'messages' => $messages, 'averageTimeToClick' => $averageTimeToClick]);
    }

    /**
     * Show campaign bounces.
     *
     * @param  int  $id
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function bounces(int $id): RedirectResponse|View
    {
        $campaign = $this->campaignRepo->find(Sendportal::currentWorkspaceId(), $id);

        if ($campaign->draft) {
            return redirect()->route('sendportal.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('sendportal.campaigns.status', $id);
        }

        $messages = $this->messageRepo->bounces(Sendportal::currentWorkspaceId(), Campaign::class, $id);

        return view('sendportal::campaigns.reports.bounces', ['campaign' => $campaign, 'messages' => $messages]);
    }

    /**
     * Show campaign unsubscribes.
     *
     * @param  int  $id
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function unsubscribes(int $id): RedirectResponse|View
    {
        $campaign = $this->campaignRepo->find(Sendportal::currentWorkspaceId(), $id);

        if ($campaign->draft) {
            return redirect()->route('sendportal.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('sendportal.campaigns.status', $id);
        }

        $messages = $this->messageRepo->unsubscribes(Sendportal::currentWorkspaceId(), Campaign::class, $id);

        return view('sendportal::campaigns.reports.unsubscribes', ['campaign' => $campaign, 'messages' => $messages]);
    }
}

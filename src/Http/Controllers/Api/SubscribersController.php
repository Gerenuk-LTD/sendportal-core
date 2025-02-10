<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Sendportal\Base\Facades\Sendportal;
use Sendportal\Base\Http\Controllers\Controller;
use Sendportal\Base\Http\Requests\Api\SubscriberStoreRequest;
use Sendportal\Base\Http\Requests\Api\SubscriberUpdateRequest;
use Sendportal\Base\Http\Resources\Subscriber as SubscriberResource;
use Sendportal\Base\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Sendportal\Base\Services\Subscribers\ApiSubscriberService;

class SubscribersController extends Controller
{
    protected SubscriberTenantRepositoryInterface $subscribers;

    protected ApiSubscriberService $apiService;

    public function __construct(
        SubscriberTenantRepositoryInterface $subscribers,
        ApiSubscriberService $apiService
    ) {
        $this->subscribers = $subscribers;
        $this->apiService = $apiService;
    }

    /**
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        $workspaceId = Sendportal::currentWorkspaceId();
        $subscribers = $this->subscribers->paginate($workspaceId, 'last_name');

        return SubscriberResource::collection($subscribers);
    }

    /**
     * @throws Exception
     */
    public function store(SubscriberStoreRequest $request): SubscriberResource
    {
        $workspaceId = Sendportal::currentWorkspaceId();
        $subscriber = $this->apiService->storeOrUpdate($workspaceId, collect($request->validated()));

        $subscriber->load('tags');

        return new SubscriberResource($subscriber);
    }

    /**
     * @throws Exception
     */
    public function show(string $email): SubscriberResource
    {
        $workspaceId = Sendportal::currentWorkspaceId();

        return new SubscriberResource($this->subscribers->findBy($workspaceId, 'email', $email, ['tags']));
    }

    /**
     * @throws Exception
     */
    public function update(SubscriberUpdateRequest $request, string $email): SubscriberResource
    {
        $workspaceId = Sendportal::currentWorkspaceId();
        $subscriber = $this->subscribers->updateBy($workspaceId, 'email', $email, $request->validated());

        return new SubscriberResource($subscriber);
    }

    /**
     * @throws Exception
     */
    public function destroy(string $email): Response
    {
        $workspaceId = Sendportal::currentWorkspaceId();
        $this->apiService->delete($workspaceId, $this->subscribers->findBy($workspaceId, 'email', $email));

        return response(null, 204);
    }
}

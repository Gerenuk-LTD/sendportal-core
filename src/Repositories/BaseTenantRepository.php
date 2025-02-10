<?php

namespace Sendportal\Base\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Sendportal\Base\Interfaces\BaseTenantInterface;

abstract class BaseTenantRepository implements BaseTenantInterface
{
    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $tenantKey = 'workspace_id';

    /**
     * Order Options
     *
     * @var array
     */
    protected $orderOptions = [];

    /**
     * Default order by
     *
     * @var string
     */
    private $orderBy = 'name';

    /**
     * Default order direction
     *
     * @var string
     */
    private $orderDirection = 'asc';

    /**
     * Return all records
     *
     * @param int $workspaceId
     * @param string $orderBy
     * @param array $relations
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function all(int $workspaceId, string $orderBy = 'id', array $relations = [], array $parameters = []): mixed
    {
        $instance = $this->getQueryBuilder($workspaceId);

        $this->parseOrder($orderBy);

        $this->applyFilters($instance, $parameters);

        return $instance->with($relations)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->get();
    }

    /**
     * Return paginated items
     *
     * @param int $workspaceId
     * @param string $orderBy
     * @param array $relations
     * @param int $paginate
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function paginate(int $workspaceId, string $orderBy = 'name', array $relations = [], int $paginate = 25, array $parameters = []): mixed
    {
        $instance = $this->getQueryBuilder($workspaceId);

        $this->parseOrder($orderBy);

        $this->applyFilters($instance, $parameters);

        return $instance->with($relations)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->paginate($paginate);
    }

    /**
     * Apply parameters, which can be extended in child classes for filtering.
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        // Should be implemented in specific repositories.
    }

    /**
     * Get many records by a field and value
     *
     * @param int $workspaceId
     * @param array $parameters
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function getBy(int $workspaceId, array $parameters, array $relations = []): mixed
    {
        $instance = $this->getQueryBuilder($workspaceId)
            ->with($relations);

        foreach ($parameters as $field => $value) {
            $instance->where($field, $value);
        }

        return $instance->get();
    }

    /**
     * List all records
     *
     * @param int $workspaceId
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     * @throws Exception
     */
    public function pluck(int $workspaceId, string $fieldName = 'name', string $fieldId = 'id'): mixed
    {
        return $this->getQueryBuilder($workspaceId)
            ->orderBy($fieldName)
            ->pluck($fieldName, $fieldId)
            ->all();
    }

    /**
     * List all records matching a field's value
     *
     * @param int $workspaceId
     * @param string $field
     * @param mixed $value
     * @param string $listFieldName
     * @param string $listFieldId
     * @return mixed
     * @throws Exception
     */
    public function pluckBy(int $workspaceId, string $field, string $value, string $listFieldName = 'name', string $listFieldId = 'id'): mixed
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        return $this->getQueryBuilder($workspaceId)
            ->whereIn($field, $value)
            ->orderBy($listFieldName)
            ->pluck($listFieldName, $listFieldId)
            ->all();
    }

    /**
     * Find a single record
     *
     * @param int $workspaceId
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function find(int $workspaceId, int $id, array $relations = []): mixed
    {
        return $this->getQueryBuilder($workspaceId)->with($relations)->findOrFail($id);
    }

    /**
     * Find a single record by a field and value
     *
     * @param int $workspaceId
     * @param string $field
     * @param mixed $value
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function findBy(int $workspaceId, string $field, string $value, array $relations = []): mixed
    {
        return $this->getQueryBuilder($workspaceId)
            ->with($relations)
            ->where($field, $value)
            ->first();
    }

    /**
     * Find a single record by multiple fields
     *
     * @param int $workspaceId
     * @param array $data
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function findByMany(int $workspaceId, array $data, array $relations = []): mixed
    {
        $model = $this->getQueryBuilder($workspaceId)->with($relations);

        foreach ($data as $key => $value) {
            $model->where($key, $value);
        }

        return $model->first();
    }

    /**
     * Find multiple models
     *
     * @param int $workspaceId
     * @param array $ids
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function getWhereIn(int $workspaceId, array $ids, array $relations = []): mixed
    {
        return $this->getQueryBuilder($workspaceId)
            ->with($relations)
            ->whereIn('id', $ids)->get();
    }

    /**
     * Create a new record
     *
     * @param int $workspaceId
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function store(int $workspaceId, array $data): mixed
    {
        $this->checkTenantData($data);

        $instance = $this->getNewInstance();

        return $this->executeSave($workspaceId, $instance, $data);
    }

    /**
     * Update the model instance
     *
     * @param int $workspaceId
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update(int $workspaceId, int $id, array $data): mixed
    {
        $this->checkTenantData($data);

        $instance = $this->find($workspaceId, $id);

        return $this->executeSave($workspaceId, $instance, $data);
    }

    /**
     * Update the model instance by field and value
     *
     * @param  int  $workspaceId
     * @param  string  $field
     * @param  string  $value
     * @param  array  $data
     * @return mixed
     * @throws Exception
     */
    public function updateBy(int $workspaceId, string $field, string $value, array $data): mixed
    {
        $this->checkTenantData($data);

        $instance = $this->findBy($workspaceId, $field, $value);

        return $this->executeSave($workspaceId, $instance, $data);
    }

    /**
     * Save the model
     *
     * @param int $workspaceId
     * @param mixed $instance
     * @param array $data
     * @return mixed
     */
    protected function executeSave(int $workspaceId, mixed $instance, array $data): mixed
    {
        $data = $this->setBooleanFields($instance, $data);

        $instance->fill($data);
        $instance->{$this->getTenantKey()} = $workspaceId;
        $instance->save();

        return $instance;
    }

    /**
     * Delete a record
     *
     * @param int $workspaceId
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function destroy(int $workspaceId, int $id): mixed
    {
        $instance = $this->find($workspaceId, $id);

        return $instance->delete();
    }

    /**
     * Delete a record by field and value
     *
     * @param  int  $workspaceId
     * @param  string  $field
     * @param  string  $value
     * @return mixed
     * @throws Exception
     */
    public function destroyBy(int $workspaceId, string $field, string $value): mixed
    {
        $instance = $this->findBy($workspaceId, $field, $value);

        return $instance->delete();
    }

    /**
     * Count of all records
     *
     * @return int
     * @throws Exception
     */
    public function count(): int
    {
        return $this->getNewInstance()->count();
    }

    /**
     * @inheritDoc
     */
    public function getModelName(): string
    {
        if (! $this->modelName) {
            throw new RuntimeException('Model has not been set in ' . get_called_class());
        }

        return $this->modelName;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getQueryBuilder(int $workspaceId): Builder
    {
        return $this->getNewInstance()->newQuery()
            ->where('workspace_id', $workspaceId);
    }

    /**
     * @inheritDoc
     */
    public function getNewInstance(): Model
    {
        $model = $this->getModelName();

        return new $model();
    }

    /**
     * Parse the order by data
     *
     * @param string $orderBy
     * @return void
     */
    protected function parseOrder(string $orderBy): void
    {
        if (substr($orderBy, -3) === 'Asc') {
            $this->setOrderDirection('asc');
            $orderBy = substr_replace($orderBy, '', -3);
        } elseif (substr($orderBy, -4) === 'Desc') {
            $this->setOrderDirection('desc');
            $orderBy = substr_replace($orderBy, '', -4);
        }

        $this->setOrderBy($orderBy);
    }

    /**
     * Set the order by field
     *
     * @param string $orderBy
     * @return void
     */
    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    /**
     * Get the order by field
     *
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * Set the order direction
     *
     * @param string $orderDirection
     * @return void
     */
    public function setOrderDirection(string $orderDirection): void
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * Get the order direction
     *
     * @return string
     */
    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    /**
     * Set the tenant key when saving data
     *
     * @param array $data
     * @return void
     * @throws Exception If Tenant value is found in data.
     */
    protected function checkTenantData(array $data): void
    {
        if (isset($data[$this->getTenantKey()])) {
            throw new Exception('Tenant value should not be provided in data.');
        }
    }

    /**
     * Returns tenant key
     *
     * @return string
     */
    protected function getTenantKey(): string
    {
        return $this->tenantKey;
    }


    /**
     * Set the model's boolean fields from the input data
     *
     * @param mixed $instance
     * @param array $data
     * @return array
     */
    protected function setBooleanFields(mixed $instance, array $data): array
    {
        foreach ($this->getModelBooleanFields($instance) as $booleanField) {
            $data[$booleanField] = Arr::get($data, $booleanField, 0);
        }

        return $data;
    }

    /**
     * Retrieve the boolean fields from the model
     *
     * @param mixed $instance
     * @return array
     */
    protected function getModelBooleanFields(mixed $instance): array
    {
        return $instance->getBooleanFields();
    }
}

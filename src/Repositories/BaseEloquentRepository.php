<?php

namespace Sendportal\Base\Repositories;

use Sendportal\Base\Interfaces\BaseEloquentInterface;

class BaseEloquentRepository implements BaseEloquentInterface
{
    /**
     * Model name
     */
    protected string $modelName;

    /**
     * Current Object instance
     */
    protected object $instance;

    /**
     * Order Options
     */
    protected array $orderOptions = [];

    /**
     * Default order by
     */
    private string $orderBy = 'name';

    /**
     * Default order direction
     */
    private string $orderDirection = 'asc';

    /**
     * Return all records
     *
     * @param  string  $orderBy
     * @param  array  $relations
     * @param  array  $parameters
     * @return mixed
     * @throws \Exception
     */
    public function all(string $orderBy = 'id', array $relations = [], array $parameters = []): mixed
    {
        $instance = $this->getQueryBuilder();

        $this->parseOrder($orderBy);

        $this->applyFilters($instance, $parameters);

        return $instance->with($relations)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->get();
    }

    /**
     * Return paginated items
     *
     * @param  string  $orderBy
     * @param  array  $relations
     * @param  int  $paginate
     * @param  array  $parameters
     * @return mixed
     * @throws \Exception
     */
    public function paginate(string $orderBy = 'name', array $relations = [], int $paginate = 25, array $parameters = []): mixed
    {
        $instance = $this->getQueryBuilder();

        $this->parseOrder($orderBy);

        $this->applyFilters($instance, $parameters);

        return $instance->with($relations)
            ->orderBy($this->getOrderBy(), $this->getOrderDirection())
            ->paginate($paginate);
    }

    /**
     * Apply parameters, which can be extended in child classes for filtering
     *
     * @param $instance
     * @param  array  $filters
     * @return mixed
     */
    protected function applyFilters($instance, array $filters = [])
    {
    }

    /**
     * Get many records by a field and value
     *
     * @param array $parameters
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function getBy(array $parameters, array $relations = []): mixed
    {
        $instance = $this->getQueryBuilder()
            ->with($relations);

        foreach ($parameters as $field => $value) {
            $instance->where($field, $value);
        }

        return $instance->get();
    }

    /**
     * List all records
     *
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     * @throws \Exception
     */
    public function pluck(string $fieldName = 'name', string $fieldId = 'id'): mixed
    {
        $instance = $this->getQueryBuilder();

        return $instance
            ->orderBy($fieldName)
            ->pluck($fieldName, $fieldId)
            ->all();
    }

    /**
     * List all records
     *
     * @param string $field
     * @param string|array $value
     * @param string $listFieldName
     * @param string $listFieldId
     * @return mixed
     * @throws \Exception
     */
    public function pluckBy(string $field, string|array $value, string $listFieldName = 'name', string $listFieldId = 'id'): mixed
    {
        $instance = $this->getQueryBuilder();

        if (! is_array($value)) {
            $value = [$value];
        }

        return $instance
            ->whereIn($field, $value)
            ->orderBy($listFieldName)
            ->pluck($listFieldName, $listFieldId)
            ->all();
    }

    /**
     * Find a single record
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function find(int $id, array $relations = []): mixed
    {
        $this->instance = $this->getQueryBuilder()->with($relations)->find($id);

        return $this->instance;
    }

    /**
     * Find a single record by a field and value
     *
     * @param string $field
     * @param string $value
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findBy(string $field, string $value, array $relations = []): mixed
    {
        $this->instance = $this->getQueryBuilder()->with($relations)->where($field, $value)->first();

        return $this->instance;
    }

    /**
     * Find a single record by multiple fields
     *
     * @param array $data
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findByMany(array $data, array $relations = []): mixed
    {
        $model = $this->getQueryBuilder()->with($relations);

        foreach ($data as $key => $value) {
            $model->where($key, $value);
        }

        $this->instance = $model->first();

        return $this->instance;
    }

    /**
     * Find multiple models
     *
     * @param array $ids
     * @param array $relations
     * @return object
     * @throws \Exception
     */
    public function getWhereIn(array $ids, array $relations = []): object
    {
        $this->instance = $this->getQueryBuilder()->with($relations)->whereIn('id', $ids)->get();

        return $this->instance;
    }

    /**
     * Create a new record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function store(array $data): object
    {
        $this->instance = $this->getNewInstance();

        return $this->executeSave($data);
    }

    /**
     * Update the model instance
     *
     * @param int $id The model id
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function update(int $id, array $data): object
    {
        $this->instance = $this->find($id);

        return $this->executeSave($data);
    }

    /**
     * Save the model
     *
     * NB - check BaseTenantRepo if any changes
     * are made here
     *
     * @param array $data
     * @return mixed
     */
    protected function executeSave(array $data): mixed
    {
        $data = $this->setBooleanFields($data);

        $this->instance->fill($data);
        $this->instance->save();

        return $this->instance;
    }

    /**
     * Delete a record
     *
     * @param int $id Model id
     * @return object model instance
     * @throws \Exception
     */
    public function destroy(int $id): object
    {
        $instance = $this->find($id);

        return $instance->delete();
    }

    /**
     * Set the model's boolean fields from the input data
     *
     * @param array $data
     * @return array
     */
    protected function setBooleanFields(array $data): array
    {
        foreach ($this->getModelBooleanFields() as $booleanField) {
            $data[$booleanField] = \Arr::get($data, $booleanField, 0);
        }

        return $data;
    }

    /**
     * Retrieve the boolean fields from the model
     *
     * @return array
     */
    protected function getModelBooleanFields(): array
    {
        return $this->instance->getBooleanFields();
    }

    /**
     * Return model name
     *
     * @return string
     * @throws \Exception If model has not been set.
     */
    public function getModelName(): string
    {
        if ($this->modelName === '' || $this->modelName === '0') {
            throw new \Exception('Model has not been set in ' . get_called_class());
        }

        return $this->modelName;
    }

    /**
     * Return a new query builder instance
     *
     * @return mixed
     * @throws \Exception#
     */
    public function getQueryBuilder(): mixed
    {
        return $this->getNewInstance()->newQuery();
    }

    /**
     * Returns new model instance
     *
     * @return mixed
     * @throws \Exception
     */
    public function getNewInstance(): mixed
    {
        $model = $this->getModelName();

        return new $model();
    }

    /**
     * Resolve order by
     *
     * @param string $orderBy
     * @return void
     */
    protected function resolveOrder(string $orderBy): void
    {
        if (! \Input::get('sort_by')) {
            $this->parseOrder($orderBy);
            return;
        }

        $this->resolveDirection();
        $this->resolveOrderBy($orderBy);
    }

    /**
     * Resolve direction
     * @return void
     */
    protected function resolveDirection(): void
    {
        $direction = strtolower(\Input::get('direction', 'asc'));

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $this->setOrderDirection($direction);
    }

    /**
     * Resolve order by
     * @return void
     */
    protected function resolveOrderBy($column): void
    {
        $orderBy = \Input::get('sort_by');

        $orderBy = \Arr::get($this->orderOptions, $orderBy, $column);

        $this->setOrderBy($orderBy);
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
     * Get count of records
     *
     * @return int
     * @throws \Exception
     */
    public function count(): int
    {
        return $this->getNewInstance()->count();
    }
}

<?php

namespace Sendportal\Base\Interfaces;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

interface BaseTenantInterface
{
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
    public function all(int $workspaceId, string $orderBy = 'id', array $relations = [], array $parameters = []): mixed;

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
    public function paginate(int $workspaceId, string $orderBy = 'name', array $relations = [], int $paginate = 25, array $parameters = []): mixed;

    /**
     * Get many records by a field and value
     *
     * @param int $workspaceId
     * @param array $parameters
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function getBy(int $workspaceId, array $parameters, array $relations = []): mixed;

    /**
     * List all records
     *
     * @param int $workspaceId
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     * @throws Exception
     */
    public function pluck(int $workspaceId, string $fieldName = 'name', string $fieldId = 'id'): mixed;

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
    public function pluckBy(int $workspaceId, string $field, string $value, string $listFieldName = 'name', string $listFieldId = 'id'): mixed;

    /**
     * Find a single record
     *
     * @param int $workspaceId
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function find(int $workspaceId, int $id, array $relations = []): mixed;

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
    public function findBy(int $workspaceId, string $field, string $value, array $relations = []): mixed;

    /**
     * Find a single record by multiple fields
     *
     * @param int $workspaceId
     * @param array $data
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function findByMany(int $workspaceId, array $data, array $relations = []): mixed;

    /**
     * Find multiple models
     *
     * @param int $workspaceId
     * @param array $ids
     * @param array $relations
     * @return mixed
     * @throws Exception
     */
    public function getWhereIn(int $workspaceId, array $ids, array $relations = []): mixed;

    /**
     * Create a new record
     *
     * @param int $workspaceId
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function store(int $workspaceId, array $data): mixed;

    /**
     * Update the model instance
     *
     * @param int $workspaceId
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update(int $workspaceId, int $id, array $data): mixed;

    /**
     * Delete a record
     *
     * @param int $workspaceId
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function destroy(int $workspaceId, int $id): mixed;

    /**
     * Count of all records
     *
     * @return int
     * @throws Exception
     */
    public function count(): int;

    /**
     * Return model name
     *
     * @return string
     * @throws RuntimeException If model has not been set.
     */
    public function getModelName(): string;

    /**
     * Return a new query builder instance.
     */
    public function getQueryBuilder(int $workspaceId): Builder;

    /**
     * Returns new model instance.
     *
     * @return Model
     */
    public function getNewInstance(): Model;

    /**
     * Set the order by field
     *
     * @param string $orderBy
     * @return void
     */
    public function setOrderBy(string $orderBy): void;

    /**
     * Get the order by field
     *
     * @return string
     */
    public function getOrderBy(): string;

    /**
     * Set the order direction
     *
     * @param string $orderDirection
     * @return void
     */
    public function setOrderDirection(string $orderDirection): void;

    /**
     * Get the order direction
     *
     * @return string
     */
    public function getOrderDirection(): string;
}

<?php

namespace Sendportal\Base\Interfaces;

interface BaseEloquentInterface
{
    /**
     * Return all items
     *
     * @param string $orderBy
     * @param array $relations
     * @param array $parameters
     * @return mixed
     */
    public function all(string $orderBy = 'id', array $relations = [], array $parameters = []): mixed;

    /**
     * Paginate items
     *
     * @param string $orderBy
     * @param array $relations
     * @param int $paginate
     * @param array $parameters
     * @return mixed
     */
    public function paginate(string $orderBy = 'name', array $relations = [], int $paginate = 50, array $parameters = []): mixed;

    /**
     * Get all items by a field
     *
     * @param array $parameters
     * @param array $relations
     * @return mixed
     */
    public function getBy(array $parameters, array $relations = []): mixed;

    /**
     * List all items
     *
     * @param string $fieldName
     * @param string $fieldId
     * @return mixed
     */
    public function pluck(string $fieldName = 'name', string $fieldId = 'id'): mixed;

    /**
     * List records limited by a certain field
     *
     * @param string $field
     * @param string|array $value
     * @param string $listFieldName
     * @param string $listFieldId
     * @return mixed
     */
    public function pluckBy(string $field, string|array $value, string $listFieldName = 'name', string $listFieldId = 'id'): mixed;

    /**
     * Find a single item
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     */
    public function find(int $id, array $relations = []): mixed;

    /**
     * Find a single item by a field
     *
     * @param string $field
     * @param string $value
     * @param array $relations
     * @return mixed
     */
    public function findBy(string $field, string $value, array $relations = []): mixed;

    /**
     * Find a single record by multiple fields
     *
     * @param array $data
     * @param array $relations
     * @return mixed
     */
    public function findByMany(array $data, array $relations = []): mixed;

    /**
     * Find multiple models
     *
     * @param array $ids
     * @param array $relations
     * @return object
     */
    public function getWhereIn(array $ids, array $relations = []): mixed;

    /**
     * Store a newly created item
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data): mixed;

    /**
     * Update an existing item
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data): mixed;

    /**
     * Permanently remove an item from storage
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id): mixed;

    /**
     * Get count of records
     *
     * @return int
     */
    public function count(): int;
}

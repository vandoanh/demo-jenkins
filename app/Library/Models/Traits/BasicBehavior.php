<?php

namespace App\Library\Models\Traits;

trait BasicBehavior
{
    use IncrementDecrement;

    /**
     * Make a new instance of the entity to query on
     *
     * @param array $with
     */
    public function make(array $with = [])
    {
        return $this->with($with);
    }

    /**
     * Find an entity by id
     *
     * @param int $id
     * @param array $with
     * @return this
     */
    public function getById($id, array $with = [])
    {
        $query = $this->make($with);

        return $query->find($id);
    }

    /**
     * Find a single entity by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getFirstBy($key, $value, array $with = [])
    {
        return $this->make($with)->where($key, '=', $value)->first();
    }

    /**
     * Find many entities by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getManyBy($key, $value, array $with = [])
    {
        return $this->make($with)->where($key, '=', $value)->get();
    }

    /**
     * Find a resource by an array of attributes
     * @param  array $attributes
     * @param boolean $withTrashed
     * @return object
     */
    public function findByAttributes(array $attributes, $withTrashed = false)
    {
        $query = $this->buildQueryByAttributes($attributes);

        if ($withTrashed) {
            return $query->withTrashed()->first();
        }

        return $query->first();
    }

    /**
     * Get resources by an array of attributes
     * @param array $attributes
     * @param null|string $orderBy
     * @param string $sortOrder
     * @param array $paging
     * @param boolean $withTrashed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByAttributes(
        array $attributes,
        $orderBy = null,
        $sortOrder = 'asc',
        $paging = [],
        $withTrashed = false
    ) {
        $query = $this->buildQueryByAttributes($attributes, $orderBy, $sortOrder);

        if ($withTrashed) {
            $query->withTrashed();
        }

        if (is_array($paging) && !empty($paging)) {
            return $this->doPaginate($query, $paging['item'], $paging['page']);
        }

        return $query->get();
    }

    /**
     * Update a resource by an array of attributes
     *
     * @param array $attributes
     * @param array $options
     * @return boolean
     */
    public function updateByAttributes(array $attributes, array $options = [])
    {
        $query = $this->buildQueryByAttributes($attributes);

        return $query->update($options);
    }

    /**
     * Delete a resource by an array of attributes
     * @param array $attributes
     * @return boolean
     */
    public function deleteByAttributes(array $attributes)
    {
        $query = $this->buildQueryByAttributes($attributes);

        return $query->delete();
    }

    /**
     * Force a hard delete a resource by an array of attributes
     * @param array $attributes
     * @return boolean
     */
    public function forceDeleteByAttributes(array $attributes)
    {
        $query = $this->buildQueryByAttributes($attributes);

        return $query->forceDelete();
    }

    /**
     * Build Query to catch resources by an array of attributes and params
     * @param array $attributes
     * @param null|string $orderBy
     * @param string $sortOrder
     * @return \Illuminate\Database\Query\Builder object
     */
    private function buildQueryByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc')
    {
        $query = $this->query();

        if (isset($attributes['with'])) {
            foreach ($attributes['with'] as $with) {
                $query->with($with);
            }
            unset($attributes['with']);
        }

        foreach ($attributes as $field => $value) {
            $query = $this->checkValue($query, $field, $value);
        }

        if ($orderBy !== null) {
            $query->orderBy($orderBy, $sortOrder);
        }

        return $query;
    }

    private function checkValue($query, $field, $value)
    {
        $arrOperation = ['<=', '>=', '<', '>', '<>', '!=', 'LIKE', 'NOTIN'];

        // If Array then using whereIn
        if (is_array($value)) {
            if (in_array(strtoupper($value[0]), $arrOperation)) {
                if (strtoupper($value[0]) == 'NOTIN') {
                    $query = $query->whereNotIn($field, $value[1]);
                } else {
                    $query = $query->where($field, strtoupper($value[0]), $value[1]);
                }
            } else {
                $query = $query->whereIn($field, $value);
            }
        } else {
            if (is_null($value)) {
                $query = $query->whereNull($field);
            } else {
                $query = $query->where($field, $value);
            }
        }

        return $query;
    }

    /**
     * Return a collection of elements who's ids match
     * @param array $ids
     * @return mixed
     */
    public function findByMany(array $ids)
    {
        $query = $this->query();

        return $query->whereIn('id', $ids)->get();
    }

    /**
     * Find a resource by a name of attribute
     * @param  string $attributeName
     * @return object
     */
    public function getValueByAttributeName($attributeName)
    {
        $data = $this->get([$attributeName])->first();

        if ($data) {
            return $data->{$attributeName};
        }

        return null;
    }

    /**
     * paginate custom
     *
     * @param  object $query
     * @param int $perPage
     * @param int $intPage
     * @return Paginator | Collection | Array
     */
    public function doPaginate($query, $perPage, $intPage)
    {
        if ($perPage == 0) {
            $perPage = 1000000000000;
        }

        // paginate
        return $query->paginate($perPage, ['*'], 'page', $intPage);
    }
}

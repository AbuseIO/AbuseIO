<?php

namespace AbuseIO\Repositories;

/**
 * Class AbstractRepository.
 */
abstract class AbstractRepository
{
    /**
     * Get the all the model records.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Get the data.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * Eager Loading of relations.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * Get the search values for a specified namespace.
     *
     * @param $namespace
     *
     * @return mixed
     */
    public function getSearchValues($namespace)
    {
        return json_decode(\Auth::user()->getOption($namespace), true);
    }

    /**
     * Save the search values for a specified namespace.
     *
     * @param $namespace
     * @param array $values
     *
     * @return void
     */
    public function saveSearchValues($namespace, $values = [])
    {
        \Auth::user()->setOption($namespace, json_encode($values));
    }

    /*
     * Magic method to call all other Model methods
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     */
//    public function __call($method, $args)
//    {
//       return call_user_func_array([$this->model, $method], $args);
//    }
}

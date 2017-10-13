<?php

namespace AbuseIO\Repositories\Contracts;

/**
 * Interface AccountRepositoryInterface
 * @package AbuseIO\Repositories
 *
 * Methods inherited from AbstractRepository:
 * @method getSearchValues($namespace)
 * @method saveSearchValues($namespace)
 *
 */
interface AccountRepositoryInterface
{
    public function create($userData);
    public function update($userData, $user);
    public function enable($user);
    public function destroy($user);
    public function disable($user);
    public function with($relations);
    public function search($searchFields = [], $sortFields = []);
    public function findById($id);
}

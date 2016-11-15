<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 15/11/16
 * Time: 14:29.
 */
namespace AbuseIO\Traits;

trait InstanceComparable
{
    /**
     * @param $instance
     *
     * @return bool
     */
    public function is($instance)
    {
        return
            get_class($this) === get_class($instance)
            && $this->getKey() === $instance->getKey();
    }
}

<?php

namespace AbuseIO\Traits;

trait InstanceComparable
{
    /*
     * @param $instance
     *
     * @return bool
     *
     * Please notice that the getKey method is not type safe. I do not know why.
     * Could be an issue with model factories, because attributes in
     * model are not explicitly cast to type.
     */
//    public function is($instance)
//    {
//        return (bool) (
//            get_class($this) === get_class($instance)
//            && $this->getKey() == $instance->getKey()
//        );
//    }
}

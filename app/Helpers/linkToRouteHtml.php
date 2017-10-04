<?php

/**
 * Function create a link_to_route with html inside the title.
 *
 * @param string $name
 * @param string $title
 * @param array  $parameters
 * @param array  $attributes
 *
 * @return string
 */
function link_to_route_html($name, $title, $parameters = [], $attributes = [])
{
    $url = route($name, $parameters);

    return '<a href="'.$url.'"'.app('html')->attributes($attributes).'>'.$title.'</a>';
}

<?php
/**
 * Function to return an url for an ash asset.
 *
 * @return string
 */
function ashAsset($url)
{
    // get the root url from the ash url
    $prefix = preg_replace(';(^http(s)?://.+?/).*;', '\1', config('main.ash.url'));

    // replace possible double slashes in the url
    $asset_url = preg_replace(';([^:]/)/;', '\1', $prefix.$url);

    return $asset_url;
}

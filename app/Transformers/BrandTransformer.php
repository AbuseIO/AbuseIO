<?php

namespace AbuseIO\Transformers;

use AbuseIO\Models\Brand;
use League\Fractal\TransformerAbstract;

class BrandTransformer extends TransformerAbstract
{
    /**
     * converts the brand object to a generic array.
     *
     * @param Brand $brand
     *
     * @return array
     */
    public function transform(Brand $brand)
    {
        return [
            'id'           => (int) $brand->id,
            'name'         => (string) $brand->name,
            'company_name' => (string) $brand->company_name,
            'created_at'   => (string) $brand->created_at,
        ];
    }
}

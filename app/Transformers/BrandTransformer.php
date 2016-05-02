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
            'company_name' => $brand->company_name,
            'created_at'   => $brand->created_at,
        ];
    }
}

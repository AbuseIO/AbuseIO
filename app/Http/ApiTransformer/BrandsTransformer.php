<?php

namespace AbuseIO\Http\ApiTransformer;

use AbuseIO\Models\Brand;

class BrandsTransformer
{
    public function transform($obj) {
        if ($obj instanceof Brand) {
            return $this->transformBrand($obj);
        }

        $return = [];
        foreach ($obj as $item) {
            $return[] = $this->transformBrand($item);
        }

        return $return;
    }

    private function transformBrand(Brand $brand) {

        return [
            'id' => (int) $brand->id,
            'company_name' => $brand->company_name,
            'created_at' => $brand->created_at
        ];
    }
}
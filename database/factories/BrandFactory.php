<?php

namespace Database\Factories;

use AbuseIO\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->name(),
            'company_name'      => $this->faker->company(),
            'logo'              => file_get_contents(\AbuseIO\Models\Brand::getDefaultLogo()->getPathname()),
            'introduction_text' => $this->faker->realText(),
            'creator_id'        => 1,
        ];
    }
}
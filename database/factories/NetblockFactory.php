<?php

namespace Database\Factories;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Database\Eloquent\Factories\Factory;

class NetblockFactory extends Factory
{
    protected $model = Netblock::class;

    public function definition()
    {
        if ($this->faker->boolean()) {
            $first_ip = $this->faker->ipv4();
            $last_ip = long2ip(ip2long($first_ip) + $this->faker->numberBetween(5, 100));
        } else {
            $first_ip = $this->faker->ipv6();
            $last_ip_int = inetPtoi($first_ip);
            $last_ip_int = bcadd($last_ip_int, $this->faker->numberBetween(1, 68719476736));
            $last_ip = inetItop($last_ip_int);
        }

        $contactId = Contact::query()->inRandomOrder()->value('id') ?? Contact::factory()->create()->id;

        return [
            'contact_id'   => $contactId,
            'first_ip'     => $first_ip,
            'last_ip'      => $last_ip,
            'first_ip_int' => inetPtoi($first_ip),
            'last_ip_int'  => inetPtoi($last_ip),
            'description'  => $this->faker->sentence($this->faker->numberBetween(3, 5)),
            'enabled'      => $this->faker->boolean(),
        ];
    }
}

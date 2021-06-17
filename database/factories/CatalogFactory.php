<?php

namespace Database\Factories;

use App\Models\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Catalog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $suplier = array ('Apple', 'Sprint', 'T-mobile');
        $name = array ('Iphone 7', 'Iphone 8', 'Iphone XS', 'Iphone 11', 'Iphone 12');
        return [
            'name' => $this->faker->randomElement($name),
            'supplier' => $this->faker->randomElement($suplier),
            'price' => $this->faker->numberBetween($min = 1000, $max = 9000)
        ];

    }
}

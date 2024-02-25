<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //  $table->id();
    //         $table->foreignId('user_id');

    //         $table->string('product_name');
    //         $table->string('product_description');

    //         $table->integer('product_price_capital');
    //         $table->integer('product_price_sell');

    //         $table->timestamps();
    public function definition()
    {
        return [
            "product_name" => $this->faker->domainName(),
            "product_description" => $this->faker->sentence(),
            "product_price_capital" => $this->faker->randomNumber(),
            "product_price_sell" => $this->faker->randomNumber(),
        ];
    }
}

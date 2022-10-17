<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class CartTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function item_can_be_added_to_the_cart()
    {
        Product::factory()->count(3)->create();

        $this->post('/cart', [
            'id' => 1,
        ])
        ->assertRedirect('/cart')
        ->assertSessionHasNoErrors()
        ->assertSessionHas('cart.0', [
            'id' => 1,
            'qty' => 1,
        ]);
    }
}
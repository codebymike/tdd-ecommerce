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

    /** @test */
    public function same_item_cannot_be_added_to_the_cart_twice()
    {
        Product::factory()->create([
            'name' => 'Taco',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'Pizza',
            'cost' => 2.1,
        ]);
        Product::factory()->create([
            'name' => 'BBQ',
            'cost' => 3.2,
        ]);

        $this->post('/cart', [
            'id' => 1, // Taco
        ]);
        $this->post('/cart', [
            'id' => 1, // Taco
        ]);
        $this->post('/cart', [
            'id' => 2, // Pizza
        ]);

        $this->assertEquals(2, count(session('cart')));

    }

    /** @test */
    public function cart_page_can_be_accessed()
    {
        Product::factory()->count(3)->create();

        $this->get('/cart')
            ->assertViewIs('cart');

    }


    public function items_added_to_the_cart_can_be_seen_in_the_cart_page()
    {
        $this->withoutExceptionHandling();

        Product::factory()->create([
            'name' => 'Taco',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'Pizza',
            'cost' => 2.1,
        ]);
        Product::factory()->create([
            'name' => 'BBQ',
            'cost' => 3.2,
        ]);

        $this->post('/cart', [
            'id' => 1, // Taco
        ]);
        $this->post('/cart', [
            'id' => 3, // BBQ
        ]);

        $cart_items = [
            [
                'id' => 1,
                'qty' => 1,
                'name' => 'Taco',
                'image' => 'some-image.jpg',
                'cost' => 1.5,
            ],
            [
                'id' => 3,
                'qty' => 1,
                'name' => 'BBQ',
                'image' => 'some-image.jpg',
                'cost' => 3.2,
            ],
        ];

        $this->get('/cart')
            ->assertViewHas('cart_items', $cart_items)
            ->assertSeeTextInOrder([
                'Taco',
                'BBQ',
            ])
            ->assertDontSeeText('Pizza');
    }


    public function item_can_be_removed_from_the_cart()
    {
        $this->withoutExceptionHandling();

        Product::factory()->create([
            'name' => 'Taco',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'Pizza',
            'cost' => 2.1,
        ]);
        Product::factory()->create([
            'name' => 'BBQ',
            'cost' => 3.2,
        ]);

        // add items to session
        session(['cart' => [
            ['id' => 2, 'qty' => 1], // Pizza
            ['id' => 3, 'qty' => 3], // Taco
        ]]);

        $this->delete('/cart/2') // remove Pizza
            ->assertRedirect('/cart')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('cart', [
                ['id' => 3, 'qty' => 3]
        ]);

        // verify that cart page is showing the expected items
        $this->get('/cart')
            ->assertSeeInOrder([
                'BBQ', // item name
                '$3.2', // cost
                '3', // qty
            ])
            ->assertDontSeeText('Pizza');

    }
}
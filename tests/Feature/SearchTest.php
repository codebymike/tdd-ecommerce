<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;


class SearchTest extends TestCase
{
    /** @test */
    public function food_search_page_is_accessible()
    {
        $this->get('/')
            ->assertOk();
    }

    /** @test */
    public function food_search_page_has_all_the_required_page_data()
    {
        // Arrange phase
        Product::factory()->count(3)->create(); // create 3 products
    }

}
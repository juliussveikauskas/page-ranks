<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Http\Resources\PageResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PagesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method of PagesController.
     *
     * @return void
     */
    public function test_index_method()
    {
        Page::factory()->count(10)->create();
        $response = $this->get(route('pages.index'));
        $response->assertStatus(200);

        $responseWithQueryParam = $this->get(route('pages.index', ['domain' => 'example.com']));
        $responseWithQueryParam->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\BancoController;
use Illuminate\Http\Request;

class CreateAccountTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateAccountTest()
    {
        $data = new Request();
        $data->name = $this->faker->name;
        $data->amount = rand(1000,9999) ;

        $banco = new BancoController();

        $nuevo = $banco->createAccount($data);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

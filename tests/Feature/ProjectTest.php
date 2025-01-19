<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testCreateAndLoginAuth(): void
    {
      
        $response = $this->post('api/user/register', [
            'name'  => 'alosh',
            'email' => 'alosh@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);

        $response->assertStatus(201);

        $responseLogin = $this->post('api/user/login', [
            'email' => 'alosh@gmail.com',
            'password' => '123456789'
        ]);

        $responseLogin->assertStatus(200);

        
        $token = auth('api')->login(User::find(1));

        $order_data =  
            [
                1 =>  [
                  "qty" => "3",
                  "product_id" => "1"
                ],
                2 =>  [
                  "qty" => "2",
                  "product_id" => "2"
                ]
            ];

        $responseOrderStore = $this->post('api/user/orders/store', [
           'requested_order' => $order_data
        ] , ['Authorization' => 'Bearer ' . $token]);
       
        $responseOrderStore->assertStatus(201);


       
        $admin_token = auth('api')->login(Admin::find(1));

        $responseOrderConfirmation = $this->post('api/admin/orders/1/update/status', [
           'status' => 'confirmed'
        ] , ['Authorization' => 'Bearer ' . $admin_token]);
    
        $responseOrderConfirmation->assertStatus(200);     

        $responseOrderPay = $this->post('api/user/orders/1/pay', [
           'payment_method_id' => 1, 'card' => '1111111111111111', 'cvv' => '123' , 'exp' => '12/28'
        ] , ['Accept' => 'application/json' , 'Authorization' => 'Bearer ' . $token]);
    
        $responseOrderPay->assertStatus(200);


    }
}

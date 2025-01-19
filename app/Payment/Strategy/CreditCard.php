<?php
namespace App\Payment\Strategy;

use App\Payment\PaymentInterface;

class CreditCard implements PaymentInterface
{ 

    private $gateway_url;
    private $api_key;
    private $api_secret;

    public function __construct($gateway_url , $api_key , $api_secret){
       $this->gateway_url = $gateway_url;
       $this->api_key = $api_key;
       $this->api_secret = $api_secret;
    }

  public function pay(int $amount)
  {
      return true;
  }
}
<?php

namespace App\Payment;

use App\Payment\PaymentInterface;
use App\Payment\Strategy\Paypal;
use App\Payment\Strategy\CreditCard;

class PaymentStrategyLink
{
    public PaymentInterface $strategy;

    public function __construct(int $paymentMethod)
    {
        
        if($paymentMethod == 1) {
           $this->strategy =  new Paypal(env('PAYPAL_URL') , env('PAYPAL_API_KEY') , env('PAYPAL_API_SECRET'));
        } else {
            $this->strategy =  new CreditCard(env('CREDITCARD_URL') , env('CREDITCARD_API_KEY') , env('CREDITCARD_API_SECRET'));
        }

    }

    public function pay($amount)
    {
        return $this->strategy->pay($amount);
    }
}
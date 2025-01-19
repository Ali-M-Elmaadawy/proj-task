<?php
namespace App\Payment;

interface PaymentInterface
{
    public function pay(int $amount);
}
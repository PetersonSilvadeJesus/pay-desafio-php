<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    public function process($data): array;
}

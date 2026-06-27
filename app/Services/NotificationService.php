<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendOrderConfirmation(int $orderId, string $userName): void
    {
        Log::info("SMS SENT — Order #{$orderId} confirmed for {$userName}. Your order is being processed.");
    }
}
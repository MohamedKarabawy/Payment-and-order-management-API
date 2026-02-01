<?php

namespace App\Pipelines;

use App\Models\Order;
use Closure;

class PreventPaidOrdersDeletion
{
    public function handle(int $id, Closure $next)
    {
        $exists = Order::where('id', $id)->where('payment_status', 'paid')->exists();

        abort_if($exists, 403);

        return $next($id);
    }
}

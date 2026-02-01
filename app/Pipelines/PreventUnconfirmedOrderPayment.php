<?php

namespace App\Pipelines;

use App\Models\Order;
use Closure;

class PreventUnconfirmedOrderPayment
{
    public function handle(int $id, Closure $next)
    {
        $exists = Order::where('id', $id)->where('status', '!=', 'completed')->exists();

        abort_if($exists, 403);

        return $next($id);
    }
}

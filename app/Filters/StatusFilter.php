<?php
namespace App\Filters;

use Closure;

class StatusFilter
{

    /**
     * filter id requests
     *
     * @param Closure $next
     * @return void
     */
    public function handle($data, Closure $next)
    {
        if ($data['status']) {
            $data['query']=$data['query']->where('status', $data['status']);
        }
        return $next($data);
    }
}

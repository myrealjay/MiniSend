<?php
namespace App\Filters;

use Closure;

class SenderFilter
{

    /**
     * filter id requests
     *
     * @param Closure $next
     * @return void
     */
    public function handle($data, Closure $next)
    {
        if ($data['sender']) {
            $data['query']=$data['query']->orWhere('from', json_encode($data['sender']));
        }
        return $next($data);
    }
}

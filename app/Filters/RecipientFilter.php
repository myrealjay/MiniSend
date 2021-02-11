<?php
namespace App\Filters;

use Closure;

class RecipientFilter
{

    /**
     * filter id requests
     *
     * @param Closure $next
     * @return void
     */
    public function handle($data, Closure $next)
    {
        if ($data['receiver']) {
            $data['query']=$data['query']->orWhere('to', json_encode($data['receiver']));
        }
        return $next($data);
    }
}

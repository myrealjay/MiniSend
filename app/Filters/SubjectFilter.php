<?php
namespace App\Filters;

use Closure;

class SubjectFilter
{

    /**
     * filter id requests
     *
     * @param Closure $next
     * @return void
     */
    public function handle($data, Closure $next)
    {
        if ($data['subject']) {
            $data['query']=$data['query']->orWhere('subject', $data['subject']);
        }
        return $next($data);
    }
}

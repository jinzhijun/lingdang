<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use  Closure;
class VerifyCsrfToken extends BaseVerifier
{
	 public function handle($request, Closure $next)
    {
        // 使用CSRF
	 	return parent::handle($request, $next);
        // 禁用CSRF
		//return $next($request);
    }
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //

    ];
}

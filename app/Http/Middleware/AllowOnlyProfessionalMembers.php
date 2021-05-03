<?php

namespace App\Http\Middleware;

use Closure;

class AllowOnlyProfessionalMembers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isCompanyPremiumOrProfessionalMember = userCompany()->isCompanyPremiumOrProfessionalMember();

        if ($isCompanyPremiumOrProfessionalMember) {
            return $next($request);
        }

        return response()->view('errors.permission_denied');
    }
}

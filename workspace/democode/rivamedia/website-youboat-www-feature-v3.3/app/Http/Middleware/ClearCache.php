<?php
    namespace App\Http\Middleware;

    use Artisan;
    use Cache;
    use Closure;

    class ClearCache
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
            Artisan::call('view:clear');
            Cache::flush();
            return $next($request);
        }
    }
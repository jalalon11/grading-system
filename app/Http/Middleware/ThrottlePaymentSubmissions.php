<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ThrottlePaymentSubmissions
{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Create a unique key for this user and school
        $key = 'payment_submissions:' . $user->id . ':' . ($user->school_id ?? 0);
        
        // Allow 3 payment submissions per hour
        $maxAttempts = 3;
        $decayMinutes = 60;
        
        // Increment the counter for this key
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            // Get the number of minutes until the lock expires
            $seconds = $this->limiter->availableIn($key);
            $minutes = ceil($seconds / 60);
            
            return redirect()->back()->with('error', 
                "Too many payment submissions. Please try again in {$minutes} " . 
                ($minutes == 1 ? 'minute' : 'minutes') . ". If you need immediate assistance, please contact the administrator."
            );
        }
        
        // Increment the counter
        $this->limiter->hit($key, $decayMinutes * 60);
        
        // Add rate limit headers to the response
        $response = $next($request);
        
        return $this->addHeaders(
            $response, 
            $maxAttempts,
            $this->limiter->retriesLeft($key, $maxAttempts),
            $this->limiter->availableIn($key)
        );
    }
    
    /**
     * Add the rate limit headers to the response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @param  int|null  $retryAfter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addHeaders(Response $response, int $maxAttempts, int $remainingAttempts, ?int $retryAfter = null): Response
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);

        if (! is_null($retryAfter)) {
            $response->headers->add([
                'Retry-After' => $retryAfter,
                'X-RateLimit-Reset' => $retryAfter,
            ]);
        }

        return $response;
    }
}

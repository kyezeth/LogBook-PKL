<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Actions to log
     */
    protected array $logActions = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Routes to exclude from logging
     */
    protected array $excludeRoutes = [
        'login',
        'logout',
        'password/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log certain HTTP methods
        if (!in_array($request->method(), $this->logActions)) {
            return $response;
        }

        // Skip excluded routes
        foreach ($this->excludeRoutes as $pattern) {
            if ($request->is($pattern)) {
                return $response;
            }
        }

        // Log the action if user is authenticated
        if (auth()->check() && $response->isSuccessful()) {
            $this->logAction($request);
        }

        return $response;
    }

    /**
     * Log the action
     */
    protected function logAction(Request $request): void
    {
        $user = auth()->user();
        $action = $this->determineAction($request);

        // Try to get the model from route binding
        $routeParams = $request->route()->parameters();
        $model = null;
        $modelType = 'App\\Models\\Unknown';
        $modelId = 0;

        foreach ($routeParams as $param) {
            if (is_object($param) && method_exists($param, 'getKey')) {
                $model = $param;
                $modelType = get_class($param);
                $modelId = $param->getKey();
                break;
            }
        }

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'auditable_type' => $modelType,
            'auditable_id' => $modelId,
            'action' => $action,
            'old_values' => null,
            'new_values' => $this->sanitizeInput($request->except(['password', 'password_confirmation', '_token'])),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
        ]);
    }

    /**
     * Determine action based on HTTP method and route
     */
    protected function determineAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()->getName() ?? '';

        if (str_contains($routeName, '.store') || $method === 'POST') {
            return 'created';
        }

        if (str_contains($routeName, '.update') || in_array($method, ['PUT', 'PATCH'])) {
            return 'updated';
        }

        if (str_contains($routeName, '.destroy') || $method === 'DELETE') {
            return 'deleted';
        }

        return strtolower($method);
    }

    /**
     * Sanitize input data for logging
     */
    protected function sanitizeInput(array $input): array
    {
        // Remove sensitive fields
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret'];
        
        foreach ($sensitiveFields as $field) {
            unset($input[$field]);
        }

        // Limit array size to prevent huge logs
        if (count($input) > 50) {
            $input = array_slice($input, 0, 50);
            $input['_truncated'] = true;
        }

        return $input;
    }
}

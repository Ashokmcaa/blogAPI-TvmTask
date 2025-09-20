<?php namespace App\Http\Middleware;

   use Illuminate\Auth\Middleware\Authenticate as Middleware;
   use Illuminate\Http\Request;

   class Authenticate extends Middleware
   {
       protected function redirectTo($request): ?string
       {
           if ($request->expectsJson()) {
               return null; // Prevent redirect for API requests
           }
           return '/login'; // Fallback for web routes (if any)
       }

   }
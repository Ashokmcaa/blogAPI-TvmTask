<?php namespace App\Exceptions;

   use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
   use Throwable;
   use Symfony\Component\HttpKernel\Exception\HttpException;

   class Handler extends ExceptionHandler
   {
       protected $dontReport = [];

       public function render($request, Throwable $exception)
       {
           if ($request->is('api/*') || $request->expectsJson()) {
               if ($exception instanceof HttpException && $exception->getStatusCode() === 404) {
                   return response()->json(['error' => 'Route not found'], 404);
               }
               return parent::render($request, $exception);
           }
           return parent::render($request, $exception);
       }
   }
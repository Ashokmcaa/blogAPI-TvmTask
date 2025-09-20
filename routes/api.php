 <?php
   use Illuminate\Support\Facades\Route;
   use App\Http\Controllers\API\UserController;
   use App\Http\Controllers\API\PostController;
   use App\Http\Controllers\API\CommentController;
   use App\Http\Controllers\API\TagController;
Route::post('/test', function() {
    return response()->json(['msg' => 'POST works']);
});

   Route::post('/register', [UserController::class, 'register']);
   Route::post('/login', [UserController::class, 'login']);
    Route::post('/posts', [PostController::class, 'store']);
       Route::put('/posts/{id}', [PostController::class, 'update']);
       Route::delete('/posts/{id}', [PostController::class, 'destroy']);
   Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

   Route::get('/posts', [PostController::class, 'index']);
   Route::get('/posts/{id}', [PostController::class, 'show']);
   Route::middleware('auth:sanctum')->group(function () {
      
   });

   Route::get('/posts/{post_id}/comments', [CommentController::class, 'index']);
   Route::middleware('auth:sanctum')->group(function () {
       Route::post('/comments', [CommentController::class, 'store']);
       Route::put('/comments/{id}', [CommentController::class, 'update']);
       Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
   });

   Route::get('/tags', [TagController::class, 'index']);
   Route::get('/tags/{id}', [TagController::class, 'show']);
   Route::middleware('auth:sanctum')->group(function () {
       Route::post('/tags', [TagController::class, 'store']);
       Route::put('/tags/{id}', [TagController::class, 'update']);
       Route::delete('/tags/{id}', [TagController::class, 'destroy']);
   });
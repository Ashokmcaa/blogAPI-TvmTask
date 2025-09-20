<?php
   namespace App\Http\Controllers\API;

   use App\Http\Controllers\Controller;
   use App\Models\Comment;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Validator;

   class CommentController extends Controller
   {
       public function __construct()
       {
           $this->middleware('auth:sanctum')->except(['index']);
       }

       public function index($post_id)
       {
           $comments = Comment::where('post_id', $post_id)->with('user')->get();
           return response()->json(['comments' => $comments], 200);
       }

       public function store(Request $request)
       {
           $validator = Validator::make($request->all(), [
               'post_id' => 'required|exists:posts,id',
               'content' => 'required|string',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 400);
           }

           $comment = Comment::create([
               'user_id' => $request->user()->id,
               'post_id' => $request->post_id,
               'content' => $request->content,
           ]);

           return response()->json(['comment' => $comment->load('user')], 201);
       }

       public function update(Request $request, $id)
       {
           $comment = Comment::find($id);
           if (!$comment) {
               return response()->json(['error' => 'Comment not found'], 404);
           }
           if ($comment->user_id !== $request->user()->id) {
               return response()->json(['error' => 'Unauthorized'], 403);
           }

           $validator = Validator::make($request->all(), [
               'content' => 'required|string',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 400);
           }

           $comment->update(['content' => $request->content]);
           return response()->json(['comment' => $comment->load('user')], 200);
       }

       public function destroy($id)
       {
           $comment = Comment::find($id);
           if (!$comment) {
               return response()->json(['error' => 'Comment not found'], 404);
           }
           if ($comment->user_id !== auth()->id()) {
               return response()->json(['error' => 'Unauthorized'], 403);
           }
           $comment->delete();
           return response()->json(['message' => 'Comment deleted'], 200);
       }
   }
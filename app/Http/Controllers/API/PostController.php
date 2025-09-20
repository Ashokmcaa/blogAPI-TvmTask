<?php
   namespace App\Http\Controllers\API;

   use App\Http\Controllers\Controller;
   use App\Models\Post;
   use App\Models\Tag;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Validator;

   class PostController extends Controller
   {
       public function __construct()
       {
           $this->middleware('auth:sanctum')->except(['index', 'show']);
       }

       public function index()
       {
           $posts = Post::with(['user', 'comments', 'tags'])->get();
           return response()->json(['posts' => $posts], 200);
       }

       public function store(Request $request)
       {
      
           $validator = Validator::make($request->all(), [
               'title' => 'required|string|max:255',
               'content' => 'required|string',
               'tag_ids' => 'array|exists:tags,id',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 400);
           }

           $post = Post::create([
               'user_id' => $request->user()->id,
               'title' => $request->title,
               'content' => $request->content,
           ]);

           if ($request->has('tag_ids')) {
               $post->tags()->sync($request->tag_ids);
           }

           return response()->json(['post' => $post->load(['user', 'tags'])], 201);
       }

       public function show($id)
       {
           $post = Post::with(['user', 'comments.user', 'tags'])->find($id);
           if (!$post) {
               return response()->json(['error' => 'Post not found'], 404);
           }
           return response()->json(['post' => $post], 200);
       }

       public function update(Request $request, $id)
       {
           $post = Post::find($id);
           if (!$post) {
               return response()->json(['error' => 'Post not found'], 404);
           }
           if ($post->user_id !== $request->user()->id) {
               return response()->json(['error' => 'Unauthorized'], 403);
           }

           $validator = Validator::make($request->all(), [
               'title' => 'required|string|max:255',
               'content' => 'required|string',
               'tag_ids' => 'array|exists:tags,id',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 400);
           }

           $post->update([
               'title' => $request->title,
               'content' => $request->content,
           ]);

           if ($request->has('tag_ids')) {
               $post->tags()->sync($request->tag_ids);
           }

           return response()->json(['post' => $post->load(['user', 'tags'])], 200);
       }

       public function destroy($id)
       {
           $post = Post::find($id);
           if (!$post) {
               return response()->json(['error' => 'Post not found'], 404);
           }
           if ($post->user_id !== auth()->id()) {
               return response()->json(['error' => 'Unauthorized'], 403);
           }
           $post->delete();
           return response()->json(['message' => 'Post deleted'], 200);
       }
   }
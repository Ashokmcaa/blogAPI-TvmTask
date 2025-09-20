<?php
   namespace App\Http\Controllers\API;

   use App\Http\Controllers\Controller;
   use App\Models\Tag;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Validator;

   class TagController extends Controller
   {
       public function __construct()
       {
           $this->middleware('auth:sanctum')->except(['index', 'show']);
       }

       public function index()
       {
           $tags = Tag::with('posts')->get();
           return response()->json(['tags' => $tags], 200);
       }

       public function store(Request $request)
       {
           $validator = Validator::make($request->all(), [
               'name' => 'required|string|max:50|unique:tags',
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 400);
           }

           $tag = Tag::create([
               'name' => $request->name,
           ]);

           return response()->json(['tag' => $tag], 201);
       }

       public function show($id)
       {
           $tag = Tag::with('posts')->find($id);
           if (!$tag) {
               return response()->json(['error' => 'Tag not found'], 404);
           }
           return response()->json(['tag' => $tag], 200);
       }

       public function update(Request $request, $id)
       {
           $tag = Tag::find($id);
           if (!$tag) {
               return response()->json(['error' => 'Tag not found'], 404);
           }

           $validator = Validator::make($request->all(), [
               'name' => 'required|string|max:50|unique:tags,name,' . $id,
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 400);
           }

           $tag->update([
               'name' => $request->name,
           ]);

           return response()->json(['tag' => $tag], 200);
       }

       public function destroy($id)
       {
           $tag = Tag::find($id);
           if (!$tag) {
               return response()->json(['error' => 'Tag not found'], 404);
           }
           $tag->delete();
           return response()->json(['message' => 'Tag deleted'], 200);
       }
   }
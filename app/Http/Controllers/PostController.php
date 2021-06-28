<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repository\PostsRepository;
use Exception;
use Validator;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $repository;
  
    public function __construct(PostsRepository $repository)
    {
        $this->repository = $repository;
    }
  
    /**
     * get list of all the posts.
     *
     * @param $request: Illuminate\Http\Request
     * @return json response
     */
    public function index()
    {
        $data = [];
        $data['user'] = $user = auth()->user();
        $data['posts'] = auth()->user() ? $user->posts : Post::all();
        
        return view('posts.index', $data);
    }
  
        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['method']  = 'POST';
        $data['route']  = route('posts.store');
        return view('posts.partials.form', $data);
    }

    /**
     * store post data to database table.
     *
     * @param $request: App\Http\Requests\CreatePostRequest
     * @return json response
     */
    public function store(Request $request)
    {
        if($request->id != null){
            $id = $request->id;
            $data = $request->except('_token');
            $this->update($id, $data);
        }else{
            $data = $request->except('_token', 'id');
            if($request->file()){
                $image = $request->file('image');
                $new_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $new_name);
                $data['image'] = $new_name;
            }
            $item = $this->repository->store($data);
            return response()->json('Success');
           
        }
        
    }
  
     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data['model']  = Post::find($id);
        $data['method']  = 'PUT';
        $data['route']  = route('posts.update',$id);
        $data['id']     = $id;
        return view('posts.partials.form', $data);
    }
    /**
     * update post data to database table.
     *
     * @param $request: App\Http\Requests\UpdatePostRequest
     * @return json response
     */
    public function update($id, $data)
    {
        if(request()->file()){
            $image = request()->file('image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $new_name);
            $data['image'] = $new_name;
        }else{
            $data['image'] = request()->image;
        }
        $item = $this->repository->update($id, $data);
        return response()->json( 'Success');
    }
  
    /**
     * get single item by id.
     * 
     * @param integer $id: integer post id.
     * @return json response.
     */
    public function show($id)
    {
        try {
            $item = $this->repository->show($id);
            return response()->json(['item' => $item]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatus());
        }
    }
 
    /**
     * delete post by id.
     * 
     * @param integer $id: integer post id.
     * @return json response.
     */
    public function destroy ($id)
    {
        try {
            $this->repository->delete($id);
            return response()->json([], 204);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatus());
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Categories;

use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function find(Request $request) {
        try{
            $pageSize = $request->query('pageSize', 10); // Default to 10 items per page
            $page = $request->query('page', 1); // Default to the first page
            $search = $request->query('search');

            $query = Categories::query();

            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            $total = $query->count();
            $totalPages = ceil($total / $pageSize);

            $query->orderBy('created_at', 'desc');

            $categories = $query->skip(($page - 1) * $pageSize)
                  ->take($pageSize)
                  ->get();
            
            $res = [
                'data' => $categories,
                'totalPage' => $totalPages,
                'page' => $page,
                'message'=>"success",
            ];
            return response()->json($res)->setStatusCode(200);
        }catch(Exception $e){
            return response()->json([
                'message' => "{$e}"], 400);
        }
        
    }

    public function findAll(Request $request) {
        try{
            $categories;
            if ($request->query('search')) {
                $categories = Categories::where('status', true)->where('title', 'LIKE', "%{$request->query('search')}%")->get();
            }else{
                $categories = Categories::where('status', true)->get();
            }
            
            $res = [
                'data'=>$categories,
                'count'=>count($categories),
                'message'=>"success",
            ];
            return response()->json($res)->setStatusCode(200);
        }catch(Exception $e){
            return response()->json([
                'message' => "{$e}"], 400);
        }
    }

    public function create(Request $request) {
        try{
            DB::beginTransaction();
            $category = Categories::where('title',$request->title)->first();
            if($category){
                return response()->json(['mess'=>'title is exits','data'=>$category])->setStatusCode(200);
            }

            $categoryNew = new Categories;
            $categoryNew->title = $request->title;
            $categoryNew->url = $request->url;
            $categoryNew->description = $request->description;
            $categoryNew->status = $request->status || 1;
            $categoryNew->createBy = $request->createBy;
            $categoryNew->updateBy = $request->updateBy;
            $categoryNew->parent_id = $request->parent_id;

            $categoryNew->save();

            DB::commit();
            return response()->json(['mess'=>'created success'])->setStatusCode(200);

        }catch(Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => "{$e}"], 400);
        }
    }

    public function delete(Request $request) {
        try{
            DB::beginTransaction();
            Categories::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(['mess'=>'delete success'])->setStatusCode(200);
        }catch(Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => "{$e}"], 400);
        }
    }

    public function update(Request $request) {
        try{
            DB::beginTransaction();
            $category = Categories::find($request->id);
            $category->title = $request->title;
            $category->url = $request->url;
            $category->parent_id = $request->parent_id;
            $category->status = $request->status;
            $category->description = $request->description;
            $category->updateBy = $request->updateBy;
            $category->save();
            DB::commit();
            return response()->json(['mess'=>'update success','category'=>$category])->setStatusCode(200);
        }catch(Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => "{$e}"], 400);
        }
    }
}

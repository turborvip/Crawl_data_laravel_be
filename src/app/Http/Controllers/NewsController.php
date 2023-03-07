<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

<<<<<<< HEAD
class NewsController extends Controller
{
    public function find() {
        return 'Hello world News!';
    }

    public function detail($id) {
        return 'id = '.$id;
=======
use App\Models\News;
use App\Models\Categories;

use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function find(Request $request) {
        try {
            $pageSize = $request->query('pageSize', 10); // Default to 10 items per page
            $page = $request->query('page', 1); // Default to the first page
            $caption = $request->query('caption'); // Get the caption from the request
            $author = $request->query('author'); // Get the author from the request
    
            $query = News::query();
    
            if (!empty($caption)) {
                $query->where('caption', 'like', '%' . $caption . '%');
            }
            
            if (!empty($author)) {
                $query->where('author', 'like', '%' . $author . '%');
            }

            $total = $query->count();
            $totalPages = ceil($total / $pageSize);
            
            $query->orderBy('created_at', 'desc');
            
            $news = $query->skip(($page - 1) * $pageSize)
                  ->take($pageSize)
                  ->get();
            $results = [
                'msg' => 'Get data news was success',
                'data' => $news,
                'totalPage' => $totalPages,
                'page' => $page
            ];
            return response()->json($results);  
    
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => 'Something went wrong....!','err'=>$e]);
        }
    }

    public function findDetail(Request $request) {
        try {
            DB::beginTransaction();
            $news = News::find($request->id);
            $news->viewHour++;
            $news->viewDaily++;
            $news->save();
            $results = [
                'msg' => 'Get data news was success',
                'data' => $news,
            ];
            DB::commit();
            return response()->json($results);  
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'msg' => 'Something went wrong....!','err'=>$e]);
        }
    }

    public function findNewsInHome(Request $request) {
        try {
            DB::beginTransaction();
            $hotNewsHour = News::orderByDesc('viewHour')->limit(5)->get();
            $newContent = News::orderByDesc('created_at')->limit(7)->get();
            $normalNews = News::orderByDesc('id')->limit(5)->get();
            $results = [
                'msg' => 'Get data news was success',
                'data' => [
                    'newContent'=>$newContent,
                    'hotNewsHour'=>$hotNewsHour,
                    'normalNews'=>$normalNews,
                ],
            ];
            DB::commit();
            return response()->json($results);  
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'msg' => 'Something went wrong....!','err'=>$e]);
        }
    }

    public function findNewsByCategory($idCategory,Request $request){
        try {
            $pageSize = $request->input('pageSize', 10); // Default to 10 items per page
            $page = (int)$request->input('page', 1); // Default to the first page
            $caption = $request->input('caption'); // Get the caption from the request
            $author = $request->input('author'); // Get the author from the request
            $filter = $request->input('filter')||null; // Get the author from the request
            $idCategory = (int)$idCategory;
            if(!$idCategory){
                return response()->json(['status' => 500, 'msg' => 'Something went wrong....!','idCategory'=>$idCategory]);
            };
            $query = News::query();
            $category = Categories::find($idCategory);
    
            if (!empty($caption)) {
                return response()->json(['status' => 500, 'msg' => 'Something went wrong....!','idCategory'=>$caption]);

                $query->where('caption', 'like', '%' . $caption . '%');
            }
            
            if (!empty($author)) {
                $query->where('author', 'like', '%' . $author . '%');
            }

            $total = $query->whereHas('categories', function ($query) use ($idCategory) {
                                $query->where('categories.id', $idCategory);
                            })
                            ->count();
            $totalPages = ceil($total / $pageSize);

            if($filter){
                if($filter == 'createdAt DESC'){
                    $query->orderBy('created_at', 'desc');
                }else if($filter == 'createdAt ASC'){
                    $query->orderBy('created_at', 'asc');
                }else if($filter == 'viewer DESC'){
                    $query->orderBy('viewDaily', 'desc');
                }
            }
            
            $news = $query->whereHas('categories', function ($query) use ($idCategory) {
                        $query->where('categories.id', $idCategory);
                    })
                    ->skip(($page - 1) * $pageSize)
                    ->take($pageSize)
                    ->get();
            $results = [
                'msg' => 'Get data news was success',
                'news' => $news,
                'category'=>$category,
                'totalPage' => $totalPages,
                'page' => $page
            ];
            return response()->json($results);  
    
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => 'Something went wrong....!','err'=>$e]);
        }
    }

    public function create(Request $request) {
        try{
            DB::beginTransaction();
            $news = News::where('caption',$request->caption)->first();
            if($news){
                return response()->json(['mess'=>'caption is exits','data'=>$news])->setStatusCode(200);
            }

            $validatedData = $request->validate([
                'caption' => 'required',
                'image' => 'required',
                'content' => 'required',
                'createBy' => 'required',
                'updateBy' => 'required',
                'categories' => 'required|array'
            ]);

            // Create new News
            $news = new News([
                'caption' => $validatedData['caption'],
                'image' => $validatedData['image'],
                'content' => $validatedData['content'],
                'createBy' => $validatedData['createBy'],
                'updateBy' => $validatedData['updateBy'],
            ]);

            // Assign optional attributes if they exist in the request data
            if ($request->description) {
                $news->description = $request->description;
            }

            if ($request->author) {
                $news->author = $request->author;
            }

            if ($request->status) {
                $news->status = $request->status;
            }

            // Save the News
            $news->save();

            // Attach Categories to News
            $categories = Categories::whereIn('id', $validatedData['categories'])->get();
            $news->categories()->attach($categories);

            DB::commit();

            return response()->json(['message' => 'Created successfully'], 201);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => "{$e}"], 500);
        }
    }

    public function update(Request $request) {
        try{
            DB::beginTransaction();

            $news = News::findOrFail($request->id);

            if(!$news){
                return response()->json(['mess'=>'Can not find new to update'])->setStatusCode(500);
            }

            $newsCheck = News::where('caption',$request->caption)->first();
            if($newsCheck && ($newsCheck->caption !== $request->caption)){
                return response()->json(['mess'=>'caption is exits','data'=>$news])->setStatusCode(200);
            }

            $validatedData = $request->validate([
                'caption' => 'required',
                'image' => 'required',
                'content' => 'required',
                'createBy' => 'required',
                'updateBy' => 'required',
                'categories' => 'required|array'
            ]);

            // Update new News
            $news->caption = $validatedData['caption'];
            $news->image = $validatedData['image'];
            $news->content = $validatedData['content'];
            $news->createBy = $validatedData['createBy'];
            $news->updateBy = $validatedData['updateBy'];

            // Assign optional attributes if they exist in the request data
            if ($request->description) {
                $news->description = $request->description;
            }

            if ($request->author) {
                $news->author = $request->author;
            }

            if ($request->status) {
                $news->status = $request->status;
            }

            // Save the News
            $news->save();

            // Attach Categories to News
            $categories = Categories::whereIn('id', $validatedData['categories'])->get();
            $news->categories()->sync($categories);

            DB::commit();

            return response()->json(['message' => 'Updated successfully'], 201);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => "{$e}"], 500);
        }
    }

    public function getCategoriesFlowNewsId(Request $request) {
        try{
            DB::beginTransaction();
            $news = News::findOrFail($request->id);
            $categories = $news->categories()->get();
            DB::commit();
            return response()->json(['data'=>$categories])->setStatusCode(200);
        }catch(Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => "{$e}"], 400);
        }
    }

    public function delete(Request $request) {
        try{
            DB::beginTransaction();
            News::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(['mess'=>'delete success'])->setStatusCode(200);
        }catch(Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => "{$e}"], 400);
        }
    }

    public function amount(){
        return response()->json(['count'=>News::count()]);
>>>>>>> ac5b240 (update)
    }
}

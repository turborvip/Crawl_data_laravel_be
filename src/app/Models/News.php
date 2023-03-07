<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class News extends Model
{
    use HasFactory;
=======
use App\Models\Categories;


class News extends Model

{
    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'news_categories', 'news_id', 'category_id');
    }

    use HasFactory;
    protected $fillable = ['caption','image','content','categories'];

>>>>>>> ac5b240 (update)
}

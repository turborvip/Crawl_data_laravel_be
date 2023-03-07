<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class Categories extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'news_category');
=======
use App\Models\News;


class Categories extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    public function news()
    {
        return $this->belongsToMany(News::class, 'news_categories');
>>>>>>> ac5b240 (update)
    }

    public function parentCategory()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }
}

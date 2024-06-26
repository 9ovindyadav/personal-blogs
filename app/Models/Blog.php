<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DynamicRelationship;

class Blog extends Model
{
    use HasFactory, DynamicRelationship;

    protected $with = ['category'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }
}

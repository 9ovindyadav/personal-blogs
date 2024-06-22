<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DynamicRelationship;

class Task extends Model
{
    use HasFactory,DynamicRelationship;

    protected $guarded = [];
}

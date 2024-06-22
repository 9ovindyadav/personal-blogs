<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DynamicRelationship;

class Project extends Model
{
    use HasFactory, DynamicRelationship;

    protected $guarded = [];
}

<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

use App\Models\Relationship;

trait DynamicRelationship
{
    public function relation($child)
    {
        $parent = explode('\\',self::class);
        $parent = strtolower(array_pop($parent));

        $relation = Relationship::where([['parent','=', $parent],['child','=', $child]])->first();
        $pivotTable = $relation->pivot_table;
        if(!Schema::hasTable($pivotTable)){
            Schema::create($pivotTable, function(Blueprint $table) use($parent, $child){
                $table->id();
                $table->string($parent.'_id');
                $table->string($child.'_id');
                $table->timestamps();
            });
        }

        return $this->belongsToMany("App\Models\\".ucfirst($child), "{$parent}_{$child}");
    }
}
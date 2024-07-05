<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

use App\Models\Relationship;

trait DynamicRelationship
{
    public function relation($class, $type, $isParent = false, $options = [])
    {
  
        $parent = explode('\\',self::class);
        $parent = strtolower(array_pop($parent));
        $child = $class;
        if($isParent){
            $child = $parent;
            $parent = $class;
        }
        
        $relation = Relationship::where([
                                            ['parent','=', $parent],
                                            ['child','=', $child],
                                            // ['type','=', $type]
                                        ])->first();
        if(!$relation){
            throw new \Exception("Relation between model {$parent} and {$child} type {$type} not found");
        }

        switch($type){
            case '1:1':
                if(!Schema::hasColumn("{$child}s","{$parent}_id")){
                    Schema::table("{$child}s", function(Blueprint $table){
                        $table->foreignId("{$parent}_id");
                    });
                }

                $foreignKey = $options['foreign_key'] ?? "{$class}_id";

                if($isParent){
                    return $this->belongsTo("App\\Models\\".ucfirst($class), $foreignKey);
                }else{
                    return $this->hasOne("App\\Models\\".ucfirst($class), $foreignKey);
                }

            case '1:M':
                if(!Schema::hasColumn("{$child}s","{$parent}_id")){
                    Schema::table("{$child}s", function(Blueprint $table){
                        $table->foreignId("{$parent}_id");
                    });
                }
                
                if($isParent){
                    return $this->belongsTo("App\\Models\\".ucfirst($class));
                }else{
                    return $this->hasMany("App\\Models\\".ucfirst($class));
                }

            case 'M:M':
                $pivotTable = $relation->pivot_table;
                if(!Schema::hasTable($pivotTable)){
                    Schema::create($pivotTable, function(Blueprint $table) use($parent, $child){
                        $table->id();
                        $table->string($parent.'_id');
                        $table->string($child.'_id');
                        $table->timestamps();
                    });
                }

                return $this->belongsToMany("App\\Models\\".ucfirst($class), "{$parent}_{$child}");
        }
    }

    public function assigned_to()
    {
        return $this->belongsTo("App\\Models\\User", "assigned_to","id")->first();
    }

    public function created_by()
    {
        return $this->belongsTo("App\\Models\\User", "created_by", "id")->first();
    }
}
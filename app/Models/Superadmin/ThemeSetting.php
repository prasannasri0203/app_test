<?php

namespace App\Models\Superadmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
class ThemeSetting extends Model
{ 
    use HasFactory,SoftDeletes,Sortable;

    public $sortable = ['id','color_name', 'background_color','status'];
}
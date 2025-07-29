<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categoryLimits()
    {
        return $this->hasMany(CategoryLimit::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLimit extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'category_id', 'limit_amount', 'period'];

    protected $casts = [
        'limit_amount' => 'decimal:2'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

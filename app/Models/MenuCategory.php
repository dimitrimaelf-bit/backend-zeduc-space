<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
    public function menuItems()
{
    // Indique qu'une catégorie possède plusieurs plats (MenuItem)
    return $this->hasMany(MenuItem::class, 'category_id');
}
}
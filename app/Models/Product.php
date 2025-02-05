<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'matricule',
        'name',
        'price',
        'stock_quantity',
        'alert_threshold',
        'category_id',
        'image_path',
        'expiration_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiration_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the sale items for the product.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function hasLowStock()
    {
        return $this->stock_quantity <= $this->alert_threshold;
    }

    public function isOutOfStock()
    {
        return $this->stock_quantity <= 0;
    }

    public function isExpired()
    {
        return $this->expiration_date && Carbon::now()->greaterThan($this->expiration_date);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'alert_threshold');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
        });
    }
}

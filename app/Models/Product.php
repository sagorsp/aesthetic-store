<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Product extends Model {
    protected $fillable=['category_id','name','slug','sku','short_description','description','price','compare_price','stock','image','gallery','is_active','is_featured'];
    protected function casts(): array { return ['price'=>'decimal:2','compare_price'=>'decimal:2','gallery'=>'array','is_active'=>'boolean','is_featured'=>'boolean']; }
    protected static function booted(): void { static::creating(function($p){ if(!$p->slug) $p->slug=Str::slug($p->name).'-'.Str::lower(Str::random(5)); }); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    public function images(): HasMany { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function variants(): HasMany { return $this->hasMany(ProductVariant::class); }
    public function activeVariants(): HasMany { return $this->variants()->where('is_active',true); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function approvedReviews(): HasMany { return $this->reviews()->where('is_approved',true); }
    public function getFinalPriceAttribute(): float { return (float)$this->price; }
    public function getAvailableStockAttribute(): int { return $this->variants()->exists() ? (int)$this->variants()->where('is_active',true)->sum('stock') : (int)$this->stock; }
}

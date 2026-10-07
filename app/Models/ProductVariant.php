<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductVariant extends Model { protected $fillable=['product_id','name','sku','color','size','price','stock','is_active']; protected function casts():array{return ['price'=>'decimal:2','is_active'=>'boolean'];} public function product():BelongsTo{return $this->belongsTo(Product::class);} public function getEffectivePriceAttribute():float{return (float)($this->price ?? $this->product->price);} }

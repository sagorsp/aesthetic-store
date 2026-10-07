<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; class DeliveryZone extends Model { protected $fillable=['district','charge','is_active']; protected function casts():array{return ['charge'=>'decimal:2','is_active'=>'boolean'];} }

<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ShipmentEvent extends Model { protected $fillable=['order_id','provider','consignment_id','tracking_code','status','payload']; protected function casts():array{return ['payload'=>'array'];} public function order():BelongsTo{return $this->belongsTo(Order::class);} }

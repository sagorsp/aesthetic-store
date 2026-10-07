<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Payment extends Model { protected $fillable=['order_id','provider','payment_id','transaction_id','status','amount','payload']; protected function casts():array{return ['amount'=>'decimal:2','payload'=>'array'];} public function order():BelongsTo{return $this->belongsTo(Order::class);} }

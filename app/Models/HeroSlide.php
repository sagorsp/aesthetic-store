<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; class HeroSlide extends Model { protected $fillable=['eyebrow','title','subtitle','button_text','button_url','image','sort_order','is_active']; protected function casts():array{return ['is_active'=>'boolean'];} }

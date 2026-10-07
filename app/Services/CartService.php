<?php
namespace App\Services;
use App\Models\Product; use App\Models\ProductVariant;
class CartService {
    public function items():array{return session('cart',[]);} private function key(Product $p,?ProductVariant $v):string{return $p->id.':'.($v?->id?:0);} 
    public function add(Product $product,int $qty=1,?ProductVariant $variant=null):void { $cart=$this->items(); $key=$this->key($product,$variant); $stock=$variant?->stock ?? $product->stock; $price=(float)($variant?->price ?? $product->price); $existing=$cart[$key]['quantity']??0; $qty=max(1,min((int)$stock,$existing+$qty)); $cart[$key]=['key'=>$key,'product_id'=>$product->id,'variant_id'=>$variant?->id,'variant_name'=>$variant?->name,'name'=>$product->name,'slug'=>$product->slug,'sku'=>$variant?->sku ?? $product->sku,'price'=>$price,'image'=>$product->image,'quantity'=>$qty,'stock'=>(int)$stock]; session(['cart'=>$cart]); }
    public function update(string $key,int $qty):void{$cart=$this->items();if(!isset($cart[$key]))return;if($qty<=0){unset($cart[$key]);}else{$cart[$key]['quantity']=min($qty,(int)$cart[$key]['stock']);}session(['cart'=>$cart]);}
    public function remove(string $key):void{$cart=$this->items();unset($cart[$key]);session(['cart'=>$cart]);} public function clear():void{session()->forget('cart');} public function subtotal():float{return array_reduce($this->items(),fn($s,$i)=>$s+$i['price']*$i['quantity'],0.0);} public function count():int{return array_sum(array_column($this->items(),'quantity'));}
}

<?php
namespace App\Services;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use RuntimeException;
class BkashService {
    private function cfg(string $key, mixed $default=null): mixed { return config('services.bkash.'.$key,$default); }
    public function enabled(): bool { return (bool)$this->cfg('enabled',false); }
    public function baseUrl(): string { return rtrim($this->cfg($this->cfg('sandbox',true)?'sandbox_url':'live_url'),'/'); }
    private function token(): string {
        if(!$this->enabled()) throw new RuntimeException('bKash is not enabled.');
        $r=Http::timeout(20)->withHeaders(['username'=>$this->cfg('username'),'password'=>$this->cfg('password')])->post($this->baseUrl().'/token/grant',['app_key'=>$this->cfg('app_key'),'app_secret'=>$this->cfg('app_secret')]);
        if(!$r->successful() || !$r->json('id_token')) throw new RuntimeException('Could not get bKash token.');
        return $r->json('id_token');
    }
    public function createPayment(Order $order): array {
        $token=$this->token();
        $r=Http::timeout(20)->withToken($token)->withHeaders(['X-APP-Key'=>$this->cfg('app_key')])->post($this->baseUrl().'/create',[
            'mode'=>'0011','payerReference'=>$order->phone,'callbackURL'=>$this->cfg('callback_url') ?: route('bkash.callback'),'amount'=>number_format((float)$order->total,2,'.',''),'currency'=>'BDT','intent'=>'sale','merchantInvoiceNumber'=>$order->order_number,
        ]);
        if(!$r->successful() || !$r->json('paymentID') || !$r->json('bkashURL')) throw new RuntimeException($r->json('statusMessage','Could not create bKash payment.'));
        $order->update(['payment_id'=>$r->json('paymentID')]);
        Payment::updateOrCreate(['order_id'=>$order->id,'payment_id'=>$r->json('paymentID')],[
            'provider'=>'bkash','status'=>'initiated','amount'=>$order->total,'payload'=>$r->json(),
        ]);
        return $r->json();
    }
    public function execute(string $paymentId): array {
        $token=$this->token();
        $r=Http::timeout(20)->withToken($token)->withHeaders(['X-APP-Key'=>$this->cfg('app_key')])->post($this->baseUrl().'/execute',['paymentID'=>$paymentId]);
        if(!$r->successful()) throw new RuntimeException('bKash execute request failed.');
        return $r->json();
    }
}

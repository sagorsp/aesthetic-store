<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Order;
class InvoiceController extends Controller { public function pdf(Order $order){$order->load('items'); if(!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) abort(500,'PDF package missing. Run: composer require barryvdh/laravel-dompdf'); return \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.pdf',compact('order'))->setPaper('a4')->download('invoice-'.$order->order_number.'.pdf');} }

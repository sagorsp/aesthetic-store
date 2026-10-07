<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void {
    Schema::table('users', function(Blueprint $t){ $t->string('admin_role',30)->nullable()->after('is_admin'); });
    Schema::create('product_images', function(Blueprint $t){ $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->string('path'); $t->unsignedInteger('sort_order')->default(0); $t->timestamps(); });
    Schema::create('product_variants', function(Blueprint $t){ $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->string('name'); $t->string('sku')->unique(); $t->string('color')->nullable(); $t->string('size')->nullable(); $t->decimal('price',12,2)->nullable(); $t->unsignedInteger('stock')->default(0); $t->boolean('is_active')->default(true); $t->timestamps(); });
    Schema::create('wishlists', function(Blueprint $t){ $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->timestamps(); $t->unique(['user_id','product_id']); });
    Schema::create('reviews', function(Blueprint $t){ $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->unsignedTinyInteger('rating'); $t->text('comment')->nullable(); $t->boolean('is_approved')->default(false); $t->timestamps(); $t->unique(['user_id','product_id']); });
    Schema::create('delivery_zones', function(Blueprint $t){ $t->id(); $t->string('district')->unique(); $t->decimal('charge',10,2); $t->boolean('is_active')->default(true); $t->timestamps(); });
    Schema::create('hero_slides', function(Blueprint $t){ $t->id(); $t->string('eyebrow')->nullable(); $t->string('title'); $t->text('subtitle')->nullable(); $t->string('button_text')->nullable(); $t->string('button_url')->nullable(); $t->string('image')->nullable(); $t->unsignedInteger('sort_order')->default(0); $t->boolean('is_active')->default(true); $t->timestamps(); });
    Schema::create('payments', function(Blueprint $t){ $t->id(); $t->foreignId('order_id')->constrained()->cascadeOnDelete(); $t->string('provider',30); $t->string('payment_id')->nullable()->index(); $t->string('transaction_id')->nullable(); $t->string('status',30)->default('initiated'); $t->decimal('amount',12,2); $t->json('payload')->nullable(); $t->timestamps(); });
    Schema::create('shipment_events', function(Blueprint $t){ $t->id(); $t->foreignId('order_id')->constrained()->cascadeOnDelete(); $t->string('provider',50)->nullable(); $t->string('consignment_id')->nullable(); $t->string('tracking_code')->nullable(); $t->string('status',80)->nullable(); $t->json('payload')->nullable(); $t->timestamps(); });
    Schema::table('order_items', function(Blueprint $t){ $t->foreignId('product_variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete(); $t->string('variant_name')->nullable()->after('sku'); });
    Schema::table('orders', function(Blueprint $t){ $t->string('courier_provider')->nullable()->after('status'); $t->string('courier_tracking_code')->nullable()->after('courier_provider'); });
} public function down(): void {
    Schema::table('orders', fn(Blueprint $t)=>$t->dropColumn(['courier_provider','courier_tracking_code']));
    Schema::table('order_items', function(Blueprint $t){ $t->dropConstrainedForeignId('product_variant_id'); $t->dropColumn('variant_name'); });
    Schema::dropIfExists('shipment_events'); Schema::dropIfExists('payments'); Schema::dropIfExists('hero_slides'); Schema::dropIfExists('delivery_zones'); Schema::dropIfExists('reviews'); Schema::dropIfExists('wishlists'); Schema::dropIfExists('product_variants'); Schema::dropIfExists('product_images');
    Schema::table('users', fn(Blueprint $t)=>$t->dropColumn('admin_role'));
}};
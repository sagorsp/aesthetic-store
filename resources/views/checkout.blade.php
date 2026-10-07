@extends('layouts.app')
@section('title','Checkout')

@section('content')
<style>
  .co-card{background:#fff;border:1px solid #eceae4;border-radius:1.5rem}
  .co-label{display:block;font-size:.78rem;font-weight:700;color:#333;margin-bottom:.45rem}
  .co-input{width:100%;border:1px solid #deddd7!important;border-radius:.9rem!important;background:#fff!important}
  .co-input:focus{outline:none!important;border-color:#C9A227!important;box-shadow:0 0 0 4px rgba(201,162,39,.10)!important}
  .payment-card{border:1.5px solid #e7e5de;border-radius:1rem;padding:1rem;cursor:pointer;transition:.18s ease}
  .payment-card.is-selected{border-color:#B91C2C;background:#fff8f8;box-shadow:0 0 0 3px rgba(185,28,44,.08)}
</style>

<div class="as-container py-7 md:py-10">
  <div class="mb-7">
    <p class="text-xs font-bold tracking-[.18em] text-[#B91C2C] uppercase mb-2">Secure checkout</p>
    <h1 class="text-3xl md:text-4xl font-black tracking-tight">Complete your order</h1>
    <p class="text-sm text-black/45 mt-2">Enter delivery details and choose your preferred payment method.</p>
  </div>

  <form action="{{ route('checkout.place') }}" method="post" class="grid lg:grid-cols-[minmax(0,1fr)_400px] gap-6 lg:gap-8 items-start" id="checkoutForm">
    @csrf

    <div class="space-y-5">
      {{-- DELIVERY --}}
      <section class="co-card p-5 md:p-6">
        <div class="flex items-start gap-3 mb-6">
          <div class="w-9 h-9 rounded-xl bg-[#faf6e8] text-[#9a7710] grid place-items-center font-bold">1</div>
          <div>
            <h2 class="font-bold text-lg">Delivery information</h2>
            <p class="text-sm text-black/45 mt-1">Use a phone number our delivery team can reach.</p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="co-label">Full name *</label>
            <input class="input co-input h-12" name="customer_name" value="{{ old('customer_name',$customer?->name) }}" placeholder="Your full name" required>
          </div>

          <div>
            <label class="co-label">Phone *</label>
            <input class="input co-input h-12" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" inputmode="tel" required>
            <p class="text-[11px] text-black/35 mt-1.5">Bangladesh mobile number</p>
          </div>

          <div>
            <label class="co-label">Email</label>
            <input class="input co-input h-12" type="email" name="email" value="{{ old('email',$customer?->email) }}" placeholder="you@example.com">
          </div>

          <div>
            <label class="co-label">District *</label>
            @if($deliveryZones->count())
              <select class="select co-input h-12" name="district" id="districtSelect" required>
                <option value="">Select district</option>
                @foreach($deliveryZones as $zone)
                  <option value="{{ $zone->district }}"
                          data-charge="{{ (float)$zone->charge }}"
                          @selected(old('district')===$zone->district)>
                    {{ $zone->district }}
                  </option>
                @endforeach
              </select>
            @else
              <input class="input co-input h-12" name="district" id="districtInput" value="{{ old('district') }}" placeholder="Dhaka" required>
            @endif
          </div>

          <div>
            <label class="co-label">Area / Thana *</label>
            <input class="input co-input h-12" name="area" value="{{ old('area') }}" placeholder="e.g. Uttara / Dhanmondi" required>
          </div>

          <div class="md:col-span-2">
            <label class="co-label">Full address *</label>
            <textarea class="textarea co-input min-h-28" name="address" placeholder="House, road, block, landmark..." required>{{ old('address') }}</textarea>
          </div>

          <div class="md:col-span-2">
            <label class="co-label">Order note</label>
            <textarea class="textarea co-input min-h-24" name="note" placeholder="Optional note for delivery">{{ old('note') }}</textarea>
          </div>
        </div>
      </section>

      {{-- PAYMENT --}}
      <section class="co-card p-5 md:p-6">
        <div class="flex items-start gap-3 mb-5">
          <div class="w-9 h-9 rounded-xl bg-[#faf6e8] text-[#9a7710] grid place-items-center font-bold">2</div>
          <div>
            <h2 class="font-bold text-lg">Payment method</h2>
            <p class="text-sm text-black/45 mt-1">Choose how you want to pay.</p>
          </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-3">
          <label class="payment-card is-selected" data-payment-card>
            <div class="flex items-start gap-3">
              <input type="radio" name="payment_method" value="cod" class="radio radio-error mt-1" checked>
              <div>
                <div class="font-bold">Cash on Delivery</div>
                <div class="text-xs text-black/45 mt-1">Pay when your parcel arrives.</div>
                <div class="text-[11px] mt-3 inline-flex rounded-full bg-[#f5f4ef] px-2 py-1">Most popular</div>
              </div>
            </div>
          </label>

          <label class="payment-card" data-payment-card>
            <div class="flex items-start gap-3">
              <input type="radio" name="payment_method" value="bkash" class="radio radio-error mt-1">
              <div>
                <div class="font-bold">bKash</div>
                <div class="text-xs text-black/45 mt-1">Secure online payment.</div>
                <div class="text-[11px] mt-3 inline-flex rounded-full bg-[#fff1f5] text-[#B91C2C] px-2 py-1">Online payment</div>
              </div>
            </div>
          </label>
        </div>
      </section>

      <div class="rounded-2xl bg-[#faf9f5] border border-[#ebe8df] p-4 text-sm text-black/55 flex gap-3">
        <span>🔒</span>
        <span>Your order information is used only to process and deliver your purchase.</span>
      </div>
    </div>

    {{-- ORDER SUMMARY --}}
    <aside class="co-card p-5 md:p-6 lg:sticky lg:top-28">
      <h2 class="font-bold text-xl">Order summary</h2>
      <p class="text-xs text-black/40 mt-1">{{ count($items) }} cart line(s)</p>

      <div class="space-y-4 mt-5 max-h-[360px] overflow-y-auto pr-1">
        @foreach($items as $item)
          <div class="flex gap-3">
            <div class="w-16 h-16 rounded-xl bg-[#f5f3ed] overflow-hidden border shrink-0">
              @if($item['image'])
                <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover" alt="">
              @else
                <div class="w-full h-full grid place-items-center text-lg">📦</div>
              @endif
            </div>

            <div class="min-w-0 flex-1">
              <div class="font-semibold text-sm leading-5">{{ $item['name'] }}</div>
              @if($item['variant_name'])
                <div class="text-xs text-black/45 mt-1">{{ $item['variant_name'] }}</div>
              @endif
              <div class="text-xs text-black/40 mt-1">Qty: {{ $item['quantity'] }}</div>
            </div>

            <div class="font-bold text-sm shrink-0">{{ config('store.symbol') }}{{ number_format($item['price']*$item['quantity']) }}</div>
          </div>
        @endforeach
      </div>

      <div class="divider my-5"></div>

      <div class="space-y-3 text-sm">
        <div class="flex justify-between gap-3">
          <span class="text-black/55">Subtotal</span>
          <strong>{{ config('store.symbol') }}<span id="subtotalAmount">{{ number_format($subtotal) }}</span></strong>
        </div>

        <div class="flex justify-between gap-3">
          <span class="text-black/55">Delivery</span>
          <strong id="deliveryAmount">Select district</strong>
        </div>
      </div>

      <div class="border-t border-dashed border-[#ddd9ce] mt-5 pt-5 flex justify-between items-end gap-3">
        <div>
          <div class="text-xs text-black/45">Total payable</div>
          <div class="text-[11px] text-black/35 mt-1">Including delivery</div>
        </div>
        <div class="text-2xl font-black text-[#B91C2C]">
          {{ config('store.symbol') }}<span id="grandTotal">{{ number_format($subtotal) }}</span>
        </div>
      </div>

      <button class="btn mt-6 h-12 bg-[#B91C2C] hover:bg-[#9f1826] text-white border-0 w-full rounded-xl">
        Place order
      </button>

      <div class="grid grid-cols-3 gap-2 mt-4 text-center text-[10px] text-black/45">
        <div class="rounded-lg bg-[#faf9f5] p-2">✓ Secure</div>
        <div class="rounded-lg bg-[#faf9f5] p-2">🚚 Delivery</div>
        <div class="rounded-lg bg-[#faf9f5] p-2">☎ Support</div>
      </div>
    </aside>
  </form>
</div>

<script>
const subtotal = {{ (float)$subtotal }};
const dhakaFallback = {{ (float)config('store.dhaka_delivery') }};
const outsideFallback = {{ (float)config('store.outside_delivery') }};
const money = n => Number(n||0).toLocaleString('en-BD', {maximumFractionDigits:2});

function updateTotals(charge){
  const delivery=document.getElementById('deliveryAmount');
  const total=document.getElementById('grandTotal');

  if(charge===null){
    delivery.textContent='Select district';
    total.textContent=money(subtotal);
    return;
  }

  delivery.textContent='{{ config('store.symbol') }}'+money(charge);
  total.textContent=money(subtotal+Number(charge));
}

const districtSelect=document.getElementById('districtSelect');
if(districtSelect){
  const recalc=()=>{
    const opt=districtSelect.options[districtSelect.selectedIndex];
    const charge=opt && opt.dataset.charge!==undefined && opt.dataset.charge!=='' ? Number(opt.dataset.charge) : null;
    updateTotals(charge);
  };
  districtSelect.addEventListener('change',recalc);
  recalc();
}

const districtInput=document.getElementById('districtInput');
if(districtInput){
  const recalc=()=>{
    const value=districtInput.value.trim().toLowerCase();
    if(!value) return updateTotals(null);
    updateTotals(value==='dhaka' ? dhakaFallback : outsideFallback);
  };
  districtInput.addEventListener('input',recalc);
  recalc();
}

document.querySelectorAll('[data-payment-card]').forEach(card=>{
  const radio=card.querySelector('input[type="radio"]');
  card.addEventListener('click',()=>{
    document.querySelectorAll('[data-payment-card]').forEach(x=>x.classList.remove('is-selected'));
    card.classList.add('is-selected');
    radio.checked=true;
  });
});
</script>
@endsection

<style>
:root{
  --as-grad-start:#ff5b2f;
  --as-grad-mid:#ff4d61;
  --as-grad-end:#ff3f86;
  --as-gradient:linear-gradient(135deg,#ff5b2f 0%,#ff4d61 52%,#ff3f86 100%);
  --as-gradient-hover:linear-gradient(135deg,#f64b28 0%,#f53e58 52%,#f23279 100%);
}

/* Old Aesthetic Store red -> new gradient palette */
.text-\[\#B91C2C\]{color:var(--as-grad-mid)!important}
.bg-\[\#B91C2C\]{background:var(--as-gradient)!important;color:#fff!important}
.border-\[\#B91C2C\]{border-color:var(--as-grad-mid)!important}

button.bg-\[\#B91C2C\],
a.bg-\[\#B91C2C\],
.btn.bg-\[\#B91C2C\]{
  background:var(--as-gradient)!important;
  border-color:transparent!important;
  color:#fff!important;
  box-shadow:0 8px 20px rgba(255,77,97,.18);
}
button.bg-\[\#B91C2C\]:hover,
a.bg-\[\#B91C2C\]:hover,
.btn.bg-\[\#B91C2C\]:hover{
  background:var(--as-gradient-hover)!important;
}

.badge.bg-\[\#B91C2C\]{
  background:var(--as-gradient)!important;
  color:#fff!important;
  border-color:transparent!important;
}

.payment-card.is-selected,
.variant-card.is-selected{
  border-color:var(--as-grad-mid)!important;
  box-shadow:0 0 0 3px rgba(255,77,97,.09)!important;
  background:#fff8fa!important;
}

#dynamicPrice,.price-brand{
  background:var(--as-gradient);
  -webkit-background-clip:text;
  background-clip:text;
  color:transparent!important;
}

header a:hover, header button:hover{color:var(--as-grad-mid)!important}

.as-brand-gradient,.as-gradient-bg{
  background:var(--as-gradient)!important;
  color:#fff!important;
}
.as-brand-text{
  background:var(--as-gradient);
  -webkit-background-clip:text;
  background-clip:text;
  color:transparent!important;
}
.as-gradient-button{
  background:var(--as-gradient)!important;
  border:0!important;
  color:#fff!important;
  border-radius:999px;
  box-shadow:0 10px 24px rgba(255,77,97,.20);
}
.as-gradient-button:hover{
  background:var(--as-gradient-hover)!important;
  transform:translateY(-1px);
}
.as-promo-strip{
  background:var(--as-gradient)!important;
  color:#fff!important;
}
.menu li > a.active,.as-sidebar-active{
  background:var(--as-gradient)!important;
  color:#fff!important;
}

/* Keep destructive actions semantic */
.btn-error,.text-error{}
</style>

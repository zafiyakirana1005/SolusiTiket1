@php
  $startsAtValue = old('starts_at', isset($promo) ? optional($promo->starts_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'));
  $endsAtValue = old('ends_at', isset($promo) ? optional($promo->ends_at)->format('Y-m-d\TH:i') : now()->addWeek()->format('Y-m-d\TH:i'));
@endphp

<div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg max-w-3xl">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-unit-md">
    <div class="md:col-span-2">
      <label class="font-label-md text-label-md text-on-surface-variant">Nama Promo / Campaign</label>
      <input name="name" value="{{ old('name', $promo->name ?? '') }}" required class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/40" placeholder="mis. Diskon Kemerdekaan 17%">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Kode Voucher (opsional)</label>
      <input name="code" value="{{ old('code', $promo->code ?? '') }}" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg uppercase" placeholder="mis. MERDEKA17">
      <span class="font-label-sm text-label-sm text-outline">Kosongkan jika promo otomatis diterapkan tanpa kode.</span>
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
      <select name="status" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
        <option value="active" @selected(old('status', $promo->status ?? 'active')=='active')>Aktif</option>
        <option value="inactive" @selected(old('status', $promo->status ?? '')=='inactive')>Nonaktif</option>
      </select>
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Tipe Potongan</label>
      <select name="type" x-data x-on:change="$el.dispatchEvent(new Event('input'))" onchange="document.getElementById('maxDiscountWrap').classList.toggle('hidden', this.value !== 'percentage')" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
        <option value="percentage" @selected(old('type', $promo->type ?? 'percentage')=='percentage')>Persentase (%)</option>
        <option value="fixed" @selected(old('type', $promo->type ?? '')=='fixed')>Nominal Tetap (Rp)</option>
      </select>
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Nilai Potongan</label>
      <input type="number" name="value" value="{{ old('value', $promo->value ?? '') }}" required min="1" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="mis. 17 (untuk %) atau 25000 (untuk Rp)">
    </div>

    <div id="maxDiscountWrap" class="{{ old('type', $promo->type ?? 'percentage') !== 'percentage' ? 'hidden' : '' }}">
      <label class="font-label-md text-label-md text-on-surface-variant">Maks. Potongan (Rp, khusus persentase)</label>
      <input type="number" name="max_discount" value="{{ old('max_discount', $promo->max_discount ?? '') }}" min="0" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="mis. 50000">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Minimal Belanja (Rp)</label>
      <input type="number" name="min_purchase" value="{{ old('min_purchase', $promo->min_purchase ?? 0) }}" min="0" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Kuota Penggunaan (opsional)</label>
      <input type="number" name="quota" value="{{ old('quota', $promo->quota ?? '') }}" min="1" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="Kosongkan = tanpa batas">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Mulai Berlaku</label>
      <input type="datetime-local" name="starts_at" value="{{ $startsAtValue }}" required class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Berakhir</label>
      <input type="datetime-local" name="ends_at" value="{{ $endsAtValue }}" required class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
    </div>

    <div class="md:col-span-2">
      <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi (opsional)</label>
      <textarea name="description" rows="3" class="mt-1 w-full px-unit-sm py-2 bg-surface-container-low rounded-lg" placeholder="Syarat & ketentuan singkat, target pelanggan, dsb.">{{ old('description', $promo->description ?? '') }}</textarea>
    </div>
  </div>

  <div class="mt-unit-lg flex items-center gap-unit-sm">
    <button type="submit" class="h-10 px-unit-lg rounded-lg bg-primary text-on-primary font-label-lg text-label-lg font-semibold shadow-sm hover:bg-primary-container">Simpan Promo</button>
    <a href="{{ route('admin.promos.index') }}" class="h-10 px-unit-lg rounded-lg bg-surface-container-low text-on-surface font-label-lg text-label-lg flex items-center">Batal</a>
  </div>
</div>

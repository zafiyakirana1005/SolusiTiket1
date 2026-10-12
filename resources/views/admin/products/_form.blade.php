@php
  $existingVariants = isset($product)
    ? $product->variants->map(fn($v) => ['name'=>$v->name,'sku'=>$v->sku,'stock'=>$v->stock,'shelf'=>$v->shelf_location])->values()
    : collect();
@endphp

<div class="bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg max-w-3xl" x-data='{ variants: @json($existingVariants->toArray()) }'>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-unit-md">

    <div class="md:col-span-2">
      <label class="font-label-md text-label-md text-on-surface-variant">Nama Produk</label>
      <input name="name" value="{{ old('name', $product->name ?? '') }}" required
             class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/40"
             placeholder="mis. The Lodge Classic Pine Hoodie">
    </div>

    <div class="md:col-span-2">
      <label class="font-label-md text-label-md text-on-surface-variant">Subjudul</label>
      <input name="subtitle" value="{{ old('subtitle', $product->subtitle ?? '') }}"
             class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg"
             placeholder="mis. Zip Hoodie Katun Hijau Lumut Lembang">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Kategori</label>
      <select name="category_id" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
        <option value="">— Tanpa Kategori —</option>
        @foreach ($categories as $cat)
          <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">SKU Utama</label>
      <input name="sku" value="{{ old('sku', $product->sku ?? '') }}" required
             class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg"
             placeholder="LMD-APP-001">
      @error('sku')
        <p class="mt-1 font-label-sm text-label-sm text-error" role="alert">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Harga Jual (Rp)</label>
      <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required min="0"
             class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg"
             placeholder="349000">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Harga Coret (Rp)</label>
      <input type="number" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price ?? '') }}" min="0"
             class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg"
             placeholder="Opsional">
      <p class="mt-1 font-label-sm text-label-sm text-outline">Isi harga sebelum diskon, harus lebih tinggi dari harga jual.</p>
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Stok (tanpa varian)</label>
      <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0"
             class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Berat (gram)</label>
      <input type="number" name="weight_grams" value="{{ old('weight_grams', $product->weight_grams ?? 500) }}" min="0"
             class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">Status</label>
      <select name="status" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg">
        <option value="draft"        @selected(old('status', $product->status ?? 'draft')=='draft')>Draft</option>
        <option value="active"       @selected(old('status', $product->status ?? '')=='active')>Aktif</option>
        <option value="out_of_stock" @selected(old('status', $product->status ?? '')=='out_of_stock')>Stok Habis</option>
        <option value="archived"     @selected(old('status', $product->status ?? '')=='archived')>Diarsipkan</option>
      </select>
    </div>

    <div class="md:col-span-2">
      <label class="font-label-md text-label-md text-on-surface-variant">Deskripsi Lengkap</label>
      <textarea name="description" rows="3" class="mt-1 w-full px-unit-sm py-2 bg-surface-container-low rounded-lg">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    {{-- ── Foto Produk ──────────────────────────────────── --}}
    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">
        Foto Depan
        <span class="text-outline font-normal ml-1">(tampil default)</span>
      </label>
      @if (isset($product) && $product->image_path)
        <div class="mt-1 mb-2 flex items-center gap-unit-sm">
          <img src="{{ asset('storage/' . $product->image_path) }}"
               class="w-20 h-20 rounded-lg object-cover border border-surface-container" alt="Foto depan saat ini">
          <span class="font-label-sm text-label-sm text-outline">Upload baru untuk mengganti.</span>
        </div>
      @endif
      <input type="file" name="image" accept="image/*"
             class="mt-1 w-full text-body-sm text-on-surface-variant file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-surface-container file:text-on-surface file:text-sm">
    </div>

    <div>
      <label class="font-label-md text-label-md text-on-surface-variant">
        Foto Belakang
        <span class="text-outline font-normal ml-1">(muncul saat hover di toko)</span>
      </label>
      @if (isset($product) && $product->image_path_back)
        <div class="mt-1 mb-2 flex items-center gap-unit-sm">
          <img src="{{ asset('storage/' . $product->image_path_back) }}"
               class="w-20 h-20 rounded-lg object-cover border border-surface-container" alt="Foto belakang saat ini">
          <span class="font-label-sm text-label-sm text-outline">Upload baru untuk mengganti.</span>
        </div>
      @else
        <p class="mt-1 font-label-sm text-label-sm text-outline">
          Belum ada. Jika tidak diisi, hover tidak akan menukar gambar.
        </p>
      @endif
      <input type="file" name="image_back" accept="image/*"
             class="mt-1 w-full text-body-sm text-on-surface-variant file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-surface-container file:text-on-surface file:text-sm">
    </div>

  </div>

  {{-- ── Varian ───────────────────────────────────────────── --}}
  <div class="mt-unit-lg pt-unit-md border-t border-surface-container">
    <input type="hidden" name="variants_present" value="1">
    <div class="flex items-center justify-between mb-unit-sm">
      <h3 class="font-headline-sm text-headline-sm text-primary">Varian Produk (opsional)</h3>
      <button type="button" @click="variants.push({name:'',sku:'',stock:0,shelf:''})"
              class="px-3 py-1.5 rounded-lg bg-surface-container-low hover:bg-surface-container font-label-sm text-label-sm font-semibold flex items-center gap-1">
        <span class="material-symbols-outlined text-[16px]">add</span> Tambah Varian
      </button>
    </div>
    @error('variant_sku')
      <p class="mb-unit-sm font-label-sm text-label-sm text-error" role="alert">{{ $message }}</p>
    @enderror
    <template x-for="(v, i) in variants" :key="i">
      <div class="grid grid-cols-1 sm:grid-cols-5 gap-unit-xs mb-unit-xs items-center">
        <input :name="'variant_name[]'" x-model="v.name" placeholder="Nama varian (L / XL)" class="h-9 px-2 bg-surface-container-low rounded-lg sm:col-span-1">
        <input :name="'variant_sku[]'"  x-model="v.sku"  placeholder="SKU varian"            class="h-9 px-2 bg-surface-container-low rounded-lg sm:col-span-1">
        <input :name="'variant_stock[]'" x-model="v.stock" type="number" placeholder="Stok" class="h-9 px-2 bg-surface-container-low rounded-lg sm:col-span-1">
        <input :name="'variant_shelf[]'" x-model="v.shelf" placeholder="Lokasi rak (B-04)"  class="h-9 px-2 bg-surface-container-low rounded-lg sm:col-span-1">
        <button type="button" @click="variants.splice(i,1)"
                class="h-9 px-2 rounded-lg text-error hover:bg-error-container/40 flex items-center justify-center sm:col-span-1">
          <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
        </button>
      </div>
    </template>
  </div>

  <div class="mt-unit-lg flex items-center gap-unit-sm">
    <button type="submit" class="h-10 px-unit-lg rounded-lg bg-primary text-on-primary font-label-lg font-semibold shadow-sm hover:bg-primary-container">
      Simpan Produk
    </button>
    <a href="{{ route('admin.products.index') }}" class="h-10 px-unit-lg rounded-lg bg-surface-container-low text-on-surface font-label-lg flex items-center">
      Batal
    </a>
  </div>
</div>

@extends('admin.layouts.app')
@section('title', 'Kategori Produk')

@section('content')
<div class="mt-unit-lg mb-unit-lg">
  <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">Kategori Produk</h1>
  <p class="font-body-md text-body-md text-on-surface-variant">Kelola pengelompokan katalog merchandise The Lodge Maribaya.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-unit-lg">
  <div class="lg:col-span-1 bg-surface-container-lowest rounded-xl shadow-sm p-unit-lg h-fit">
    <h2 class="font-headline-sm text-headline-sm text-primary mb-unit-sm">Tambah Kategori</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col gap-unit-sm">
      @csrf
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant">Nama Kategori</label>
        <input name="name" required class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="mis. Kopi & Teh Herbal">
      </div>
      <div>
        <label class="font-label-md text-label-md text-on-surface-variant">Icon (Material Symbol, opsional)</label>
        <input name="icon" class="mt-1 w-full h-10 px-unit-sm bg-surface-container-low rounded-lg" placeholder="mis. local_cafe">
      </div>
      <button type="submit" class="h-10 px-unit-lg rounded-lg bg-primary text-on-primary font-label-lg text-label-lg font-semibold mt-1">Simpan Kategori</button>
    </form>
  </div>

  <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-surface-container-low h-11 text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
          <th class="px-unit-md py-2">Nama Kategori</th>
          <th class="px-unit-md py-2 text-center">Jumlah Produk</th>
          <th class="px-unit-md py-2 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-surface-container">
        @forelse ($categories as $cat)
        <tr class="hover:bg-surface-container-low/60">
          <td class="px-unit-md py-3 font-medium text-on-surface">{{ $cat->name }}</td>
          <td class="px-unit-md py-3 text-center">
            <span class="px-2.5 py-0.5 rounded-full bg-surface-container font-label-sm text-label-sm text-on-surface-variant">{{ $cat->products_count }}</span>
          </td>
          <td class="px-unit-md py-3 text-right">
            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" class="inline">
              @csrf @method('DELETE')
              <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-error-container hover:text-error">
                <span class="material-symbols-outlined text-[18px]">delete</span>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="3" class="py-8 text-center text-on-surface-variant">Belum ada kategori.</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="p-unit-md">{{ $categories->links() }}</div>
  </div>
</div>
@endsection

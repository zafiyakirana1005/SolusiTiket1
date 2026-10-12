@extends('admin.layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="mt-unit-lg mb-unit-md">
  <nav class="flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
    <a href="{{ route('admin.products.index') }}" class="hover:text-primary">Produk</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-on-surface font-medium">Tambah Baru</span>
  </nav>
  <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight mt-1">Tambah Produk Baru</h1>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @include('admin.products._form')
</form>
@endsection

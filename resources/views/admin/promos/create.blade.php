@extends('admin.layouts.app')
@section('title', 'Buat Promo Baru')

@section('content')
<div class="mt-unit-lg mb-unit-md">
  <nav class="flex items-center gap-1.5 font-label-sm text-label-sm text-outline">
    <a href="{{ route('admin.promos.index') }}" class="hover:text-primary">Promo &amp; Popup</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-on-surface font-medium">Buat Baru</span>
  </nav>
  <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight mt-1">Buat Promo / Campaign Baru</h1>
</div>

<form action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @include('admin.promos._form')
</form>
@endsection

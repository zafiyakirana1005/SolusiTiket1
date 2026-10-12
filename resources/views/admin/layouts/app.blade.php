<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>@yield('title', 'Dashboard') · The Lodge Maribaya Backoffice</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet"/>
<style>
  html,body{margin:0;padding:0;}
  body{overscroll-behavior:none;}
  main>:first-child{margin-top:0!important;}
  main>:last-child{margin-bottom:0!important;}
  ::-webkit-scrollbar{display:none;}
  .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 500,'GRAD' 0,'opsz' 24;}
  [x-cloak]{display:none!important;}
</style>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config={darkMode:"class",theme:{extend:{
"colors":{"surface-container-low":"#f4f3f0","surface-dim":"#dbdad7","on-surface":"#1a1c1a","tertiary-fixed-dim":"#feb874","primary-fixed-dim":"#adcebc","on-secondary":"#ffffff","surface-variant":"#e3e2e0","primary-container":"#1a382b","secondary":"#436653","on-surface-variant":"#424844","background":"#faf9f6","surface-tint":"#466556","secondary-fixed":"#c5ecd4","outline-variant":"#c1c8c2","tertiary":"#301800","on-tertiary-fixed-variant":"#6a3b00","tertiary-container":"#4d2a00","on-secondary-container":"#496c59","on-tertiary-fixed":"#2d1600","on-error":"#ffffff","surface-container-lowest":"#ffffff","on-primary":"#ffffff","surface-container-highest":"#e3e2e0","secondary-fixed-dim":"#a9cfb9","surface-container":"#efeeeb","error-container":"#ffdad6","on-primary-fixed":"#022115","on-error-container":"#93000a","tertiary-fixed":"#ffdcbf","primary":"#032217","error":"#ba1a1a","inverse-on-surface":"#f2f1ee","on-tertiary":"#ffffff","inverse-surface":"#2f312f","on-tertiary-container":"#cd8d4e","secondary-container":"#c5ecd4","outline":"#727974","on-primary-fixed-variant":"#2f4d3f","on-primary-container":"#81a291","surface":"#faf9f6","surface-bright":"#faf9f6","surface-container-high":"#e9e8e5","inverse-primary":"#adcebc","on-background":"#1a1c1a","on-secondary-fixed-variant":"#2b4e3d","on-secondary-fixed":"#002113","primary-fixed":"#c8ead7"},
"borderRadius":{"DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px"},
"spacing":{"unit-xl":"2rem","sidebar-width":"260px","header-height":"64px","unit-xs":"0.5rem","sidebar-collapsed":"76px","unit-2xs":"0.25rem","unit-2xl":"2.5rem","unit-lg":"1.5rem","unit-md":"1rem","gutter-table":"1.25rem","unit-3xl":"3.5rem","unit-sm":"0.75rem"},
"fontFamily":{"headline-md":["Plus Jakarta Sans"],"body-md":["Plus Jakarta Sans"],"display":["Plus Jakarta Sans"],"headline-lg":["Plus Jakarta Sans"],"headline-sm":["Plus Jakarta Sans"],"body-lg":["Plus Jakarta Sans"],"label-md":["Plus Jakarta Sans"],"label-sm":["Plus Jakarta Sans"],"tabular-numeric":["Plus Jakarta Sans"],"title-md":["Plus Jakarta Sans"],"label-lg":["Plus Jakarta Sans"],"body-sm":["Plus Jakarta Sans"]},
"fontSize":{"headline-md":["22px",{"lineHeight":"28px","letterSpacing":"-0.01em","fontWeight":"600"}],"body-md":["14px",{"lineHeight":"20px","letterSpacing":"0em","fontWeight":"400"}],"display":["36px",{"lineHeight":"44px","letterSpacing":"-0.02em","fontWeight":"700"}],"headline-lg":["28px",{"lineHeight":"36px","letterSpacing":"-0.02em","fontWeight":"700"}],"headline-sm":["18px",{"lineHeight":"24px","letterSpacing":"-0.005em","fontWeight":"600"}],"body-lg":["16px",{"lineHeight":"24px","letterSpacing":"0em","fontWeight":"400"}],"label-md":["12px",{"lineHeight":"16px","letterSpacing":"0.02em","fontWeight":"600"}],"label-sm":["11px",{"lineHeight":"14px","letterSpacing":"0.04em","fontWeight":"700"}],"tabular-numeric":["14px",{"lineHeight":"20px","letterSpacing":"0em","fontWeight":"500"}],"title-md":["16px",{"lineHeight":"22px","letterSpacing":"0em","fontWeight":"600"}],"label-lg":["14px",{"lineHeight":"20px","letterSpacing":"0.01em","fontWeight":"600"}],"body-sm":["13px",{"lineHeight":"18px","letterSpacing":"0em","fontWeight":"400"}]}
}}}
</script>
@stack('head')
</head>
<body class="bg-background font-body-md text-body-md text-on-surface antialiased" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">

@include('admin.partials.sidebar')
<div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden" aria-hidden="true"></div>

<div class="lg:pl-[260px]">
  @include('admin.partials.header')

  <main class="w-full pt-header-height px-unit-lg bg-background min-h-screen">
    <div class="flex flex-col w-full pb-unit-3xl">

      @if (session('success'))
        <div class="mt-unit-md p-unit-sm rounded-lg bg-secondary-container text-on-secondary-container font-label-lg text-label-lg flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">check_circle</span>
          {{ session('success') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="mt-unit-md p-unit-sm rounded-lg bg-error-container text-on-error-container font-label-lg text-label-lg">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')

    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@stack('scripts')
</body>
</html>

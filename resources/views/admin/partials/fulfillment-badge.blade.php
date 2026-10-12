@php
  $map = [
    'pending_payment' => ['bg-[#FFF8EC]','text-[#965E00]','border-[#F3DFC0]'],
    'paid' => ['bg-[#EAF5EE]','text-[#1E6B3D]','border-[#C6E7D2]'],
    'packing' => ['bg-[#EBF4F7]','text-[#1D5A72]','border-[#C5E0EA]'],
    'ready_to_ship' => ['bg-[#EBF4F7]','text-[#1D5A72]','border-[#C5E0EA]'],
    'shipped' => ['bg-[#F3EDF9]','text-[#5E2F89]','border-[#DCBFEF]'],
    'completed' => ['bg-[#E6F4EA]','text-[#137333]','border-[#B7E1CD]'],
    'cancelled' => ['bg-[#FDF0ED]','text-[#A82E1E]','border-[#F8CCC4]'],
  ];
  $c = $map[$status] ?? ['bg-surface-container','text-on-surface-variant','border-outline-variant'];
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border {{ $c[0] }} {{ $c[1] }} {{ $c[2] }} font-label-sm text-label-sm font-semibold uppercase tracking-wide">
  <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', $c[1]) }}"></span>
  {{ \App\Models\Order::fulfillmentStatusLabel($status) }}
</span>

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $promos = $query->latest()->paginate(10)->withQueryString();

        $all = Promo::all();
        $countAll = $all->count();
        $countActive = $all->filter(fn ($p) => $p->computedStatus() === 'active')->count();
        $countExpired = $all->filter(fn ($p) => in_array($p->computedStatus(), ['expired', 'quota_reached']))->count();
        $totalUsage = $all->sum('used_count');

        return view('admin.promos.index', compact('promos', 'countAll', 'countActive', 'countExpired', 'totalUsage'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Promo::create($data);

        return redirect()->route('admin.promos.index')->with('success', 'Promo/campaign baru berhasil dibuat.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $this->validateData($request, $promo->id);

        $promo->update($data);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return back()->with('success', 'Promo berhasil dihapus.');
    }

    public function toggleStatus(Promo $promo)
    {
        $promo->update([
            'status' => $promo->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status promo berhasil diperbarui.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50|unique:promos,code' . ($ignoreId ? ",$ignoreId" : ''),
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
            'max_discount' => 'nullable|integer|min:0',
            'min_purchase' => 'nullable|integer|min:0',
            'quota' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
            'status' => 'required|in:active,inactive',
            'popup_enabled' => 'required|boolean',
        ]);
    }
}

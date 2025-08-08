<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiletFiyati;
use Illuminate\Http\Request;

class BiletFiyatiController extends Controller
{
    public function index()
    {
        $fiyatlar = BiletFiyati::all();
        return view('admin.bilet_fiyatlari.index', compact('fiyatlar'));
    }

    public function edit($id)
    {
        $fiyat = BiletFiyati::findOrFail($id);
        return view('admin.bilet_fiyatlari.edit', compact('fiyat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fiyat' => 'required|numeric|min:0',
        ]);

        $fiyat = BiletFiyati::findOrFail($id);
        $fiyat->update([
            'fiyat' => $request->fiyat,
        ]);

        return redirect()->route('admin.bilet_fiyatlari.index')->with('success', 'Fiyat güncellendi.');
    }
}

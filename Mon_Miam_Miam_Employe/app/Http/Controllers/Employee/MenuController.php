<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $plats = MenuItem::orderBy('nom')->get();
        return view('employe.menu.index', compact('plats'));
    }

    public function update(Request $request, MenuItem $plat)
    {
        $plat->disponible = $request->boolean('disponible');
        $plat->save();
        return redirect()->route('employe.menu.index')->with('success', 'Disponibilité du plat mise à jour.');
    }
}

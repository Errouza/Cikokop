<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Show form to create a new menu item (admin-only page, not linked in UI)
    public function create()
    {
        return view('admin.add_menu');
    }

    // Admin: list all menus
    public function index()
    {
        $menus = Menu::orderBy('created_at', 'desc')->get();
        return view('admin.menus_index', compact('menus'));
    }

    // Store new menu item
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'nullable|string|max:100',
            'gambar_url' => 'nullable|url',
            'gambar' => 'nullable|image|max:2048',
        ]);

        // If an image file was uploaded, store it in the public disk and set gambar_url
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menus', 'public');
            // Storage::url returns /storage/menus/...
            $data['gambar_url'] = Storage::url($path);
        }

        Menu::create($data);

        return redirect()->route('admin.menu.create')->with('success', 'Menu item berhasil ditambahkan');
    }
}

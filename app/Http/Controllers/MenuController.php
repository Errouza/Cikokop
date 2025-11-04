<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        // Order by id ascending as requested
        $menus = Menu::orderBy('id', 'asc')->get();
        return view('admin.menus_index', compact('menus'));
    }

    // Show edit form for a menu item
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.edit_menu', compact('menu'));
    }

    // Update menu
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        // Protect against oversized POST/uploads that get discarded by PHP
        $postMax = $this->phpSizeToBytes(ini_get('post_max_size'));
        $uploadMax = $this->phpSizeToBytes(ini_get('upload_max_filesize'));
        $contentLength = (int) ($request->server('CONTENT_LENGTH') ?? 0);
        if ($contentLength > $postMax || $contentLength > $uploadMax) {
            return back()->withInput()->withErrors(['gambar' => 'Ukuran file melebihi batas server. Maksimum upload: ' . ini_get('upload_max_filesize')]);
        }

        // Debug log to see if file arrived
        Log::info('Menu update request received', [
            'id' => $id,
            'hasFile' => $request->hasFile('gambar'),
            'fileName' => $request->file('gambar') ? $request->file('gambar')->getClientOriginalName() : null,
        ]);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'nullable|string|max:100',
            'gambar_url' => 'nullable|string|max:1000',
            // Accept only specific file extensions
            'gambar' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
            'remove_image' => 'nullable|in:1',
        ]);

        // Build explicit update data to avoid putting UploadedFile into mass assignment
        $updateData = [
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga' => $validated['harga'],
            'kategori' => $validated['kategori'] ?? null,
        ];

        // Handle removal of existing image
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            if ($menu->gambar_url) {
                $relative = ltrim(str_replace('/storage/', '', $menu->gambar_url), '/');
                Storage::disk('public')->delete($relative);
            }
            $updateData['gambar_url'] = null;
        }

        // If user provided an image URL (and didn't request removal), use it
        if ($request->filled('gambar_url') && !($request->has('remove_image') && $request->input('remove_image') == '1')) {
            $updateData['gambar_url'] = $request->input('gambar_url');
        }

        // Handle new upload
        if ($request->hasFile('gambar')) {
            // delete old
            if ($menu->gambar_url) {
                $relative = ltrim(str_replace('/storage/', '', $menu->gambar_url), '/');
                Storage::disk('public')->delete($relative);
            }
            $path = $request->file('gambar')->store('menus', 'public');
            $updateData['gambar_url'] = Storage::url($path);

            // Log stored path
            Log::info('Menu image uploaded for update', ['menu_id' => $menu->id, 'path' => $path, 'url' => $updateData['gambar_url']]);
        }

        $menu->update($updateData);

        return redirect()->route('admin.menu.index')->with('success', 'Menu updated');
    }

    // Delete a menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->gambar_url) {
            $relative = ltrim(str_replace('/storage/', '', $menu->gambar_url), '/');
            Storage::disk('public')->delete($relative);
        }
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu deleted');
    }

    // Magic recommender page (asks a few short questions and suggests menus)
    public function magic()
    {
        $menus = Menu::all();
        // Pass menus to view as JSON for client-side recommendation
        return view('magic', compact('menus'));
    }

    // Store new menu item
    public function store(Request $request)
    {
        // Protect against oversized POST/uploads that get discarded by PHP
        $postMax = $this->phpSizeToBytes(ini_get('post_max_size'));
        $uploadMax = $this->phpSizeToBytes(ini_get('upload_max_filesize'));
        $contentLength = (int) ($request->server('CONTENT_LENGTH') ?? 0);
        if ($contentLength > $postMax || $contentLength > $uploadMax) {
            return back()->withInput()->withErrors(['gambar' => 'Ukuran file melebihi batas server. Maksimum upload: ' . ini_get('upload_max_filesize')]);
        }

        // Debug log to see if file arrived
        Log::info('Menu store request received', [
            'hasFile' => $request->hasFile('gambar'),
            'fileName' => $request->file('gambar') ? $request->file('gambar')->getClientOriginalName() : null,
        ]);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'nullable|string|max:100',
            'gambar_url' => 'nullable|string|max:1000',
            // Accept only specific file extensions
            'gambar' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $createData = [
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga' => $validated['harga'],
            'kategori' => $validated['kategori'] ?? null,
        ];

        // If an image file was uploaded, store it in the public disk and set gambar_url
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menus', 'public');
            $createData['gambar_url'] = Storage::url($path);

            // Log stored path
            Log::info('Menu image uploaded for store', ['path' => $path, 'url' => $createData['gambar_url']]);
        } elseif (!empty($validated['gambar_url'])) {
            $createData['gambar_url'] = $validated['gambar_url'];
        }

        Menu::create($createData);

        return redirect()->route('admin.menu.create')->with('success', 'Menu item berhasil ditambahkan');
    }

    // Helper to convert php size string (e.g. 2M) to bytes
    private function phpSizeToBytes($size)
    {
        if (is_numeric($size)) return (int) $size;
        $unit = strtolower(substr($size, -1));
        $value = (int) substr($size, 0, -1);
        switch ($unit) {
            case 'g':
                return $value * 1024 * 1024 * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'k':
                return $value * 1024;
            default:
                return (int) $size;
        }
    }
}

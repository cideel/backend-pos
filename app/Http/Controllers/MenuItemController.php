<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    // Mengambil semua item menu
    public function index()
    {
        return response()->json(MenuItem::all());
    }

    // Mengambil item menu berdasarkan tipe
    public function getByType($type)
    {
        $menuItems = MenuItem::where('item_type', $type)->get();
        return response()->json($menuItems);
    }
    // Mengambil item menu berdasarkan label
    public function getByLabel($label)
    {
        $menuItems = MenuItem::where('item_label', $label)->get();
        return response()->json($menuItems);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function stokbarang()
    {
        return view("inventory.stokbarang");
    }
    public function barangmasuk()
    {
        return view("inventory.barangmasuk");
    }

    public function barangkeluar()
    {
        return view("inventory.barangkeluar");
    }
    public function editbarang()
    {
        return view("inventory.editbarang");
    }
}

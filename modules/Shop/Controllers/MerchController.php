<?php

namespace Modules\Shop\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductUploadService;
use Modules\Shop\Models\MerchProduct;
use Illuminate\Http\Request;

class MerchController extends Controller
{
    protected $productUploadService;

    public function __construct()
    {
        $this->productUploadService = new ProductUploadService;
    }

    public function index()
    {
        $merchProducts = MerchProduct::all();
        return view('shop::merch.index', compact('merchProducts'));
    }

    public function create()
    {
        return view('shop::merch.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'size' => 'required|string',
            'color' => 'required|string',
            'inventory' => 'required|integer',
        ]);

        $data['is_merch'] = true;
        return $this->productUploadService->store($data, 1);
    }

    public function edit($id)
    {
        $merchProduct = MerchProduct::findOrFail($id);
        return view('shop::merch.edit', compact('merchProduct'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'size' => 'required|string',
            'color' => 'required|string',
            'inventory' => 'required|integer',
        ]);

        $merchProduct = MerchProduct::findOrFail($id);
        $merchProduct->update($data);
        return redirect()->route('merch.index');
    }

    public function destroy($id)
    {
        $merchProduct = MerchProduct::findOrFail($id);
        $merchProduct->delete();
        return redirect()->route('merch.index');
    }
} 
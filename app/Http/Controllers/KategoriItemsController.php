<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;

        $query = KategoriItem::query();

        if (!empty($nama)) $query->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($kode)) $query->where('kode', 'LIKE', '%' . $kode . '%');

        $data = $query->select('id', 'nama', 'kode')->orderBy('id')->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = KategoriItem::findOrFail($id);
        }
        return view('kategori_items.form.index', ['item' => $item, 'method' => $method]);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
        ]);

        if ($method == 'new') {
            $item = new KategoriItem();
        } else {
            $item = KategoriItem::findOrFail($id);
        }

        $item->nama = $request->nama;
        $item->kode = $request->kode;
        $item->save();

        return redirect('kategori-items')->with('success', 'Kategori berhasil disimpan.');
    }

    public function singleView($id)
    {
        $kategori = KategoriItem::with('masterItems.kategoriItems')->findOrFail($id);
        return view('kategori_items.single.index', ['data' => $kategori]);
    }

    public function delete($id)
    {
        KategoriItem::findOrFail($id)->delete();
        return redirect('kategori-items')->with('success', 'Kategori berhasil dihapus.');
    }

    public function downloadPdf($id)
    {
        $kategori = KategoriItem::with('masterItems')->findOrFail($id);
        $pdf = Pdf::loadView('kategori_items.pdf.index', ['data' => $kategori, 'printed_at' => now()]);
        return $pdf->download('kategori-' . $kategori->kode . '.pdf');
    }
}

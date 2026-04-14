<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::with('kategoriItems');

        if (!empty($kode)) $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        // FIX BUG: harga filter - hargamax harus dibandingkan dengan harga_beli, bukan harga jual
        if (!empty($hargamin) && !empty($hargamax)) {
            $data_search->where('harga_beli', '>=', $hargamin)->where('harga_beli', '<=', $hargamax);
        } elseif (!empty($hargamin)) {
            $data_search->where('harga_beli', '>=', $hargamin);
        } elseif (!empty($hargamax)) {
            $data_search->where('harga_beli', '<=', $hargamax);
        }

        $data_search = $data_search->orderBy('id')->get();

        $result = $data_search->map(function ($item) {
            return [
                'kode'       => $item->kode,
                'nama'       => $item->nama,
                'jenis'      => $item->jenis,
                'harga_beli' => $item->harga_beli,
                'laba'       => $item->laba,
                'supplier'   => $item->supplier,
                'foto'       => $item->foto ? asset('storage/' . $item->foto) : null,
                'kategori'   => $item->kategoriItems->pluck('nama')->implode(', '),
            ];
        });

        return response()->json([
            'status' => 200,
            'data'   => $result,
        ]);
    }

    public function formView($method, $id = 0)
    {
        $kategoriList = KategoriItem::orderBy('nama')->get();

        if ($method == 'new') {
            $item = [];
            $selectedKategori = [];
        } else {
            $item = MasterItem::with('kategoriItems')->findOrFail($id);
            $selectedKategori = $item->kategoriItems->pluck('id')->toArray();
        }

        return view('master_items.form.index', compact('item', 'method', 'kategoriList', 'selectedKategori'));
    }

    public function singleView($kode)
    {
        $data = MasterItem::with('kategoriItems')->where('kode', $kode)->firstOrFail();
        return view('master_items.single.index', compact('data'));
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem();
            $kode = MasterItem::withTrashed()->count('id') + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = MasterItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->nama       = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba       = $request->laba;
        $data_item->kode       = $kode;
        $data_item->supplier   = $request->supplier;
        $data_item->jenis      = $request->jenis;

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // hapus foto lama jika ada
            if ($data_item->foto && Storage::disk('public')->exists($data_item->foto)) {
                Storage::disk('public')->delete($data_item->foto);
            }
            $path = $request->file('foto')->store('master_items/foto', 'public');
            $data_item->foto = $path;
        }

        $data_item->save();

        // Sync kategori (many-to-many)
        $kategoriIds = $request->input('kategori', []);
        $data_item->kategoriItems()->sync($kategoriIds);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::findOrFail($id)->delete();
        return redirect('master-items');
    }

    public function downloadExcel()
    {
        $items = MasterItem::with('kategoriItems')->orderBy('id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Master Items');

        // Header - menggunakan array untuk memudahkan pengaturan kolom dan styling
        $headers = ['A' => 'No', 'B' => 'Nama Kategori', 'C' => 'Nama Items', 'D' => 'Nama Supplier', 'E' => 'Harga', 'F' => 'Laba', 'G' => 'Harga Jual'];
        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
        }

        // Data rows
        foreach ($items as $index => $item) {
            $row = $index + 2;
            $kategoriNama = $item->kategoriItems->pluck('nama')->implode(', ');
            $hargaJual = round($item->harga_beli + $item->harga_beli * $item->laba / 100);

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $kategoriNama);
            $sheet->setCellValue('C' . $row, $item->nama);
            $sheet->setCellValue('D' . $row, $item->supplier);
            $sheet->setCellValue('E' . $row, $item->harga_beli);
            $sheet->setCellValue('F' . $row, $item->laba . '%');
            $sheet->setCellValue('G' . $row, $hargaJual);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'master-items-' . date('Ymd-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba       = rand(10, 99);
            $item->kode       = $kode;
            $item->supplier   = $this->getRandomSupplier();
            $item->jenis      = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        return $array[rand(0, 4)];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        return $array[rand(0, 4)];
    }
}

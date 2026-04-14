@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-group mb-2 d-flex gap-2">
                <a href="{{ url('kategori-items') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
                <a href="{{ url('kategori-items/download-pdf') }}/{{ $data->id }}" class="btn btn-danger">⬇ Download PDF</a>
                <a href="{{ url('kategori-items/form/edit') }}/{{ $data->id }}" class="btn btn-info">Edit</a>
                <a href="{{ url('kategori-items/delete') }}/{{ $data->id }}" class="btn btn-danger"
                    onclick="return confirm('Yakin ingin menghapus kategori ini?');">Hapus</a>
            </div>
            <div class="card mb-3">
                <div class="card-header">Detail Kategori</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width:200px">Nama Kategori</th>
                            <td>{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kode Kategori</th>
                            <td>{{ $data->kode }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Daftar Item dalam Kategori ini</div>
                <div class="card-body">
                    @if($data->masterItems->isEmpty())
                    <p class="text-muted">Belum ada item dalam kategori ini.</p>
                    @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga Beli</th>
                                <th>Laba</th>
                                <th>Harga Jual</th>
                                <th>Supplier</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->masterItems as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><a href="{{ url('master-items/view') }}/{{ $item->kode }}">{{ $item->kode }}</a></td>
                                <td>{{ $item->nama }}</td>
                                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ $item->laba }}%</td>
                                <td>Rp {{ number_format(round($item->harga_beli + $item->harga_beli * $item->laba / 100), 0, ',', '.') }}</td>
                                <td>{{ $item->supplier }}</td>
                                <td>{{ $item->jenis }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

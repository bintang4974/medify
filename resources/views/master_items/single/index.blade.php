@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{ url('master-items') }}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Master Item</div>

                <div class="card-body">
                    @if($data->foto)
                    <div class="mb-3 text-center">
                        <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto {{ $data->nama }}"
                            class="img-thumbnail" style="max-height: 200px;">
                    </div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th>Kode</th>
                            <td>{{ $data->kode }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th>Harga Beli</th>
                            <td>Rp {{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Laba</th>
                            <td>{{ $data->laba }}%</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>Rp {{ number_format(round($data->harga_beli + $data->harga_beli * $data->laba / 100), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>{{ $data->supplier }}</td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>{{ $data->jenis }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                @forelse($data->kategoriItems as $kat)
                                <span class="badge bg-secondary me-1">{{ $kat->nama }}</span>
                                @empty
                                <span class="text-muted">-</span>
                                @endforelse
                            </td>
                        </tr>
                    </table>
                    <a class="btn btn-info" href="{{ url('master-items/form/edit') }}/{{ $data->id }}">Edit</a>
                    <a class="btn btn-danger" href="{{ url('master-items/delete') }}/{{ $data->id }}"
                        onclick="return confirm('Yakin ingin menghapus item ini?');">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

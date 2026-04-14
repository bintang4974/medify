@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{ url('kategori-items') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card">
                <div class="card-header">
                    {{ $method == 'new' ? 'Buat Kategori Baru' : 'Edit Kategori' }}
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Nama Kategori</label>
                            <input type="text" class="form-control" name="nama" required
                                value="{{ old('nama', $item->nama ?? '') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label>Kode Kategori</label>
                            <input type="text" class="form-control" name="kode" required
                                value="{{ old('kode', $item->kode ?? '') }}">
                        </div>
                        <button class="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

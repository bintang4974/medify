<form method="POST" enctype="multipart/form-data">
    @csrf

    @if($method == 'edit')
    <div class="form-group mb-3">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{ $item->kode ?? '' }}">
    </div>
    @endif

    <div class="form-group mb-3">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
    </div>

    <div class="form-group mb-3">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required value="{{ $item->harga_beli ?? '' }}">
    </div>

    <div class="form-group mb-3">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required value="{{ $item->laba ?? '' }}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group mb-3">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group mb-3">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    {{-- TASK 1: Field Foto --}}
    <div class="form-group mb-3">
        <label>Foto</label>
        @if(!empty($item) && !empty($item->foto))
        <div class="mb-2">
            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Item" class="img-thumbnail" style="max-height: 150px;">
        </div>
        @endif
        <input type="file" class="form-control" name="foto" accept="image/*">
        <small class="text-muted">Format: JPG, PNG, GIF. Kosongkan jika tidak ingin mengubah foto.</small>
    </div>

    {{-- TASK 3: Field Kategori (many-to-many) --}}
    <div class="form-group mb-3">
        <label>Kategori</label>
        <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
            @forelse($kategoriList as $kategori)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="kategori[]"
                    value="{{ $kategori->id }}" id="kat_{{ $kategori->id }}"
                    @if(in_array($kategori->id, $selectedKategori)) checked @endif>
                <label class="form-check-label" for="kat_{{ $kategori->id }}">
                    {{ $kategori->nama }} ({{ $kategori->kode }})
                </label>
            </div>
            @empty
            <p class="text-muted mb-0">Belum ada kategori. <a href="{{ url('kategori-items/form/new') }}" target="_blank">Buat kategori baru</a>.</p>
            @endforelse
        </div>
    </div>

    <button class="btn btn-primary mt-3">Submit</button>
</form>

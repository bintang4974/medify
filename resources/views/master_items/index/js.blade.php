<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [7, 8] }
            ]
        });
        getData();
    });

    $('.btn-get-data').click(function () {
        getData();
    });

    function getData() {
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val();
        var filter_nama = $('#filter-nama').val();
        var filter_harga_min = $('#filter-harga-min').val();
        var filter_harga_max = $('#filter-harga-max').val();
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url("master-items/search") }}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: {
                kode: filter_kode,
                nama: filter_nama,
                hargamin: filter_harga_min,
                hargamax: filter_harga_max
            },
            success: function (results) {
                var data = results.data;

                $.each(data, function (index, item) {
                    var harga_jual = Math.round(item.harga_beli + item.harga_beli * item.laba / 100);
                    var viewUrl = '{{ url("master-items/view/") }}/' + item.kode;
                    var viewBtn = `<a href="${viewUrl}" class="btn btn-primary btn-sm">View</a>`;
                    var fotoHtml = item.foto
                        ? `<img src="${item.foto}" alt="foto" style="height:40px;width:40px;object-fit:cover;">`
                        : '<span class="text-muted">-</span>';

                    dataTableObj.row.add([
                        item.kode,
                        item.nama,
                        item.kategori || '-',
                        item.jenis,
                        'Rp ' + item.harga_beli.toLocaleString('id-ID'),
                        'Rp ' + harga_jual.toLocaleString('id-ID'),
                        item.supplier,
                        fotoHtml,
                        viewBtn,
                    ]).draw(true);
                });
                $('#loading-filter').hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data');
                $('#loading-filter').hide();
            }
        });
    }
</script>

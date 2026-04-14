<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'asc']],
            columnDefs: [{ orderable: false, targets: [2] }]
        });
        getData();
    });

    $('.btn-get-data').click(function () {
        getData();
    });

    function getData() {
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url("kategori-items/search") }}',
            dataType: 'json',
            data: {
                nama: $('#filter-nama').val(),
                kode: $('#filter-kode').val(),
            },
            success: function (results) {
                $.each(results.data, function (index, item) {
                    var viewUrl = '{{ url("kategori-items/view/") }}/' + item.id;
                    var viewBtn = `<a href="${viewUrl}" class="btn btn-primary btn-sm">View</a>`;
                    dataTableObj.row.add([item.nama, item.kode, viewBtn]).draw(true);
                });
                $('#loading-filter').hide();
            },
            error: function () {
                alert('Terjadi kesalahan server.');
                $('#loading-filter').hide();
            }
        });
    }
</script>

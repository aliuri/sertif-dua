<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sertifikat Online Polkesyo</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>  
        <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script> 
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{url('/')}}">Cieee Admin kerjaa :D</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="btn btn-info" href="{{url('/abang-siomay')}}">Sertifikat</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="card mx-auto p-2" style="width: 98%; margin-top: 20px;">
            <div class="card-body">
                <!-- Bulk Delete Button -->
                <button id="bulk-delete" class="btn btn-danger mb-3">Hapus yang di checklist</button>

                <div class="table-responsive">
                    <table id="peserta" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all">All</th> <!-- Checkbox Select All -->
                                <th>No</th>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Parent Sertif</th>
                                <th>Action</th>  <!-- Action column for delete button -->
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
        <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#peserta').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('data.peserta.get') }}",
                    type: "GET",
                    dataType: 'json',
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memuat data.');
                    }
                },
                columns: [
                    {data: null, orderable: false, searchable: false, render: function(data, type, row) {
                        return `<input type="checkbox" class="row-check" value="${row.id}">`;  // Checkbox per row
                    }},
                    {data: null, name: 'DT_RowIndex', orderable: false, searchable: false, render: function(data, type, row, meta) {
                        return meta.row + 1;  // Nomor urut
                    }},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name', render: function(data) {
                        return data ? data : 'kosong';
                    }},
                    {data: 'email', name: 'email', render: function(data) {
                        return data ? data : 'kosong';
                    }},
                    {data: 'sertif.file', name: 'sertif.file', render: function(data) {  
                        return data ? data : 'kosong';  
                    }},
                    {data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                        return `
                            <button class="btn btn-danger delete-btn" data-id="${row.id}">Delete</button>
                        `;
                    }},
                ],
                "pageLength": 10,
                "pagingType": "full_numbers",  // Pagination dengan full numbers
                "language": {
                    "paginate": {
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "emptyTable": "No data available",
                }
            });

            // Delete action for individual row
            $('body').on('click', '.delete-btn', function() {
                var pesertaId = $(this).data('id');
                if (confirm('Are you sure you want to delete this peserta?')) {
                    $.ajax({
                        url: '/peserta/' + pesertaId,
                        type: 'DELETE',
                        success: function(response) {
                            alert(response.success);
                            table.ajax.reload();  // Reload the table after deletion
                        },
                        error: function(response) {
                            alert(response.responseJSON.error);
                        }
                    });
                }
            });

            // Bulk delete action (for selected rows)
            $('#bulk-delete').click(function() {
                var selectedIds = [];
                $('.row-check:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    if (confirm('Are you sure you want to delete selected peserta?')) {
                        $.ajax({
                            url: '{{ route('peserta.bulkDelete') }}',
                            type: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                alert(response.success);
                                table.ajax.reload();  // Reload the table after deletion
                            },
                            error: function(response) {
                                alert(response.responseJSON.error);
                            }
                        });
                    }
                } else {
                    alert('No peserta selected');
                }
            });

            // Select all rows checkbox
            $('#select-all').change(function() {
                var isChecked = $(this).prop('checked');
                $('.row-check').prop('checked', isChecked);
            });
        });
        </script>
    </body>
</html>

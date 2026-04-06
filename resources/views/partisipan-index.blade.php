@extends('layouts.app')
@section('content')
    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
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
                <div class="d-flex justify-content-between mb-3">
                    <button id="bulk-delete" class="btn btn-danger">Hapus yang di checklist</button>
                    <button id="add" class="btn btn-primary">Tambah Partisipan</button>
                </div>

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
@include('partisipan-form')
@endsection
@push('script')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#peserta').DataTable({
                ajax: {
                    url: "{{ route('data.peserta.get') }}",
                    type: "GET",
                    data: function(d) {
                        // Kirim parameter tambahan untuk pagination
                        d.page = (d.start / d.length) + 1; // Hitung halaman
                        d.per_page = d.length; // Jumlah entries per halaman
                    },
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
                processing: true,
                serverSide: true,
                pageLength: 10, // Default page length
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]], // Opsi show entries
                pagingType: "full_numbers",
                language: {
                    paginate: {
                        first: "Ngarep",
                        last: "Buri",
                        next: "Maju",
                        previous: "Mundur"
                    },
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    emptyTable: "Data ne kosong boss",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    search: "Pencarian:",
                    zeroRecords: "Data ne ra cocok boss",
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

            // Load sertif options for dropdown
            function loadSertifOptions() {
                $.ajax({
                    url: "{{ route('sertif.all') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">-- Pilih Sertif --</option>';
                        $.each(data, function(key, value) {
                            options += '<option value="' + value.id + '">' + value.file + '</option>';
                        });
                        $('#sertif_id').html(options);
                        // Reinitialize Select2 after loading options
                        $('#sertif_id').select2({
                            placeholder: 'Cari dan pilih sertif...',
                            allowClear: true,
                            dropdownParent: $('#ajaxModel')
                        });
                    }
                });
            }

            // Load sertif options on page load
            loadSertifOptions();

            // Add button click handler
            $('#add').click(function() {
                $('#saveBtn').val("create-peserta");
                $('#peserta_id').val('');
                $('#FormPartisipan').trigger("reset");
                $('#modelHeading').html("Tambah Partisipan");
                $('#ajaxModel').modal('show');
                // Reinitialize Select2 when modal is shown
                setTimeout(function() {
                    $('#sertif_id').select2({
                        placeholder: 'Cari dan pilih sertif...',
                        allowClear: true,
                        dropdownParent: $('#ajaxModel')
                    });
                }, 100);
            });

            // Form submission
            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                let data = new FormData($("#FormPartisipan")[0]);

                $.ajax({
                    data: data,
                    url: "{{ route('peserta.store') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#ajaxModel').modal('hide');
                        $('#FormPartisipan').trigger("reset");
                        table.ajax.reload();
                        alert('Partisipan berhasil ditambahkan');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alert('Terjadi kesalahan saat menyimpan');
                        $('#saveBtn').html('Simpan');
                    }
                });
            });
        });
        </script>
@endpush

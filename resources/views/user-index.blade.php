@extends('layouts.app')
@section('content')
    <div class="card mx-auto p-2" style="width: 98%; margin-top: 20px;">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button id="addUser" class="btn btn-primary">Tambah User Baru</button>
                <a class="btn btn-info mx-auto" href="{{url('/abang-siomay')}}">Sertifikat</a>
                <h4 class="card-title">Manajemen User</h4>
            </div>

            <div class="table-responsive">
                <table id="userTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@include('user-form')
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        var table = $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#addUser').click(function () {
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $('#modelHeading').html("Tambah User");
            $('#userModal').modal('show');
            $('#password').attr('required', true); // Password wajib untuk user baru
        });

        $('body').on('click', '.editUser', function () {
            var user_id = $(this).data('id');
            $.get("{{ route('users.index') }}" + '/' + user_id + '/edit', function (data) {
                $('#modelHeading').html("Edit User");
                $('#userModal').modal('show');
                $('#user_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#password').attr('required', false); // Password opsional saat edit
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Menyimpan..');
            
            $.ajax({
                data: $('#userForm').serialize(),
                url: "{{ route('users.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#userForm').trigger("reset");
                    $('#userModal').modal('hide');
                    table.ajax.reload();
                    $('#saveBtn').html('Simpan');
                },
                error: function (xhr) {
                    console.log('Error:', xhr);
                    $('#saveBtn').html('Simpan');
                    
                    // Cek jika ada pesan error spesifik dari server
                    if(xhr.responseJSON && xhr.responseJSON.error) {
                        alert('Gagal: ' + xhr.responseJSON.error);
                        return;
                    }
                    alert('Gagal menyimpan data.');
                }
            });
        });

        $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data("id");
            if(confirm("Apakah Anda yakin ingin menghapus user ini?")){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('users.index') }}" + '/' + user_id,
                    success: function (data) {
                        table.ajax.reload();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
    </script>
@endpush
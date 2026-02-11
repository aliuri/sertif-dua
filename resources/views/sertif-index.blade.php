@extends('layouts.app')
@section('content')
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            {{-- <a class="navbar-brand" href="{{url('/')}}">Cieee Admin kerjaa :D</a> --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="btn btn-primary navbar-brand" href="javascript:void(0)" id="tambah">Tambah sertif</a>
                <a class="btn btn-info" href="{{route('data.peserta.view')}}">Data semua peserta</a>
            </div>
            </div>
        </div>
        </nav>
        <div class="card mx-auto p-2" style="width: 98%; margin-top: 20px;">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="sertif" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>File</th>
                                <th>Page 2</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
</div>
@include('sertif-form')
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    // Gunakan setTimeout untuk memastikan semua library sudah fully loaded
    setTimeout(function() {
        console.log('Document ready, initializing DataTables...');
        console.log('jQuery version:', $.fn.jquery);
        console.log('DataTables available?', typeof $.fn.DataTable);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        try {
            var table = $('#sertif').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('kang.siomay')}}",
                    error: function(xhr, error, code) {
                        console.error('DataTables AJAX error:', error, code);
                        console.error('Response:', xhr.responseText);
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'id', name: 'id', orderable: true, searchable: false},
                    {data: 'file', name: 'file', orderable: true, searchable: true},
                    {data: 'page_2', name: 'page_2', orderable: true, searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                error: function(xhr, status, error) {
                    console.error('Table error:', error);
                }
            });
            console.log('DataTables initialized successfully');
        } catch (error) {
            console.error('Error initializing DataTables:', error);
        }
    }, 100);
    
    $('#tambah').click(function () {
        $('#saveBtn').val("create-Jurusan");
        $('#Jurusan_id').val('');
        $('#JurusanForm').trigger("reset");
        $('#ajaxModel').modal('show');
    });

    $('body').on('click', '.editSertif', function () {
        var sertif_id = $(this).data('id');
        $.get("jual-siomay" +'/' + sertif_id +'/edit', function (data) {
            $('#modelHeading').html("Edit sertif");
            $('#saveBtn').val("edit-sertif");
            $('#ajaxModel').modal('show');
            $('#sertif_id').val(data.id);
            $('#margin_top').val(data.margin_top);
            $('#margin_right').val(data.margin_right);
            $('#margin_left').val(data.margin_left);
            $('#peserta_top').val(data.peserta_top);
            $('#peserta_right').val(data.peserta_right);
            $('#peserta_left').val(data.peserta_left);
            $('#file_edit').val(data.file);

            if ((data.page_two !== 0) && (data.page_two !== null)) {
                console.log(data.page_two);
                console.log("checked");
                $(".page_2").prop("checked", true);
            } else {
                $(".page_2").prop("checked", false);
            }

            document.getElementById('file_upload').style.display = 'none';
            document.getElementById('excel_upload').style.display = 'none';
        });
    });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
        let data = new FormData($("#Formsertif")[0]);

        $.ajax({
            data: data,
            url: "{{ route('makan.siomay') }}",
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (data) {
                $('#ajaxModel').modal('hide');
                location.reload();
            },
            error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save Changes');
            }
        });
    });

    $('#excelFile').change(function() {
        const file = this.files[0];
        if (!file) return;
        
        const fileType = file.type;
        const maxSize = 5242880; // 5MB
        
        // Reset error message
        $('#excelError').html('');
        
        // Validate file type
        const validTypes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv'
        ];
        
        if (!validTypes.includes(fileType)) {
            $('#excelError').html('Please upload a valid Excel file (.xlsx, .xls, or .csv)');
            this.value = '';
            return;
        }
        
        // Validate file size
        if (file.size > maxSize) {
            $('#excelError').html('File size should not exceed 5MB');
            this.value = '';
            return;
        }
    });
});
</script>
@endpush
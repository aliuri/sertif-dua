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
        {{-- <link href="{{ asset('assets/css/styles.css') }}"rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
        {{-- datatables --}}
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>  
        <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script> 
    </head>
    <body>
        <div class="table-responsive">
            <a class="btn btn-primary" href="javascript:void(0)" id="tambah">Tambah</a><br><br>
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
    </body>
@include('sertif-form')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script>
    $(function () {

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
        
        var table = $('#sertif').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                    url: "{{route('kang.siomay')}}",
                },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'id', name: 'id', orderable: true, searchable: false},
                {data: 'file', name: 'file', orderable: true, searchable: true},
                {data: 'page_2', name: 'page_2', orderable: true, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        
        $('#tambah').click(function () {
        $('#saveBtn').val("create-Jurusan");
        $('#Jurusan_id').val('');
        $('#JurusanForm').trigger("reset");
        // $('#modelHeading').html("Create New Jurusan");
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
          $('#size_nama').val(data.size_nama);
          $('#size_peserta').val(data.size_peserta);
          $('#file_edit').val(data.file);

          if ((data.page_two !== 0) & (data.page_two !== null)) {
              console.log(data.page_two);
              console.log("checked");
              $(".page_2").prop("checked", true);
            }else{
                $(".page_2").prop("checked", false);
            }
          
          if ((data.rata_huruf!== 0) & (data.rata_huruf!== null)) {
              console.log(data.rata_huruf);
              console.log("checked");
              $(".rata_kiri").prop("checked", true);
            }else{
                $(".rata_kiri").prop("checked", false);
            }
          document.getElementById('file_upload').style.display = 'none';

      })
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

            //   $('#sertif').trigger("reset");
              $('#ajaxModel').modal('hide');
            //   console.log(data.success);
            // $('#success').text(data.success);
            // document.getElementById('success').style.display = 'block';
            location.reload();
            //   table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
            //   $('#nameError').text(data.responseJSON.errors.name);
            //   $('#fileError').text(data.responseJSON.errors.file);
            //   $('#id_jenisError').text(data.responseJSON.errors.id_jenis);
              $('#saveBtn').html('Save Changes');
          }
      });
    });

    });
</script>
</html>

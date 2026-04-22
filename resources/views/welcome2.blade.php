<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Serpo 2.0 - Sertifikat Online Polkesyo</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/logo baru.png') }}" sizes="16x16">
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="{{ asset('wowdash/assets/css/remixicon.css') }}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/bootstrap.min.css') }}">
  <!-- main css -->
  <link rel="stylesheet" href="{{ asset('wowdash/assets/css/style.css') }}">
</head>
  <body>

<div class="custom-bg">
    <div class="container container--xl">
        <div class="d-flex align-items-center justify-content-between py-24">
            <a href="{{ url('/') }}" class="">
                <img width="200" src="{{ asset('assets/logo baru.png') }}" alt="">
            </a>
        </div>

        <div class="py-res-120">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <h3 class="mb-32 max-w-1000-px">Serpo 2.0</h3>
                    <p class="text-neutral-500 max-w-700-px text-lg">Sertifikat Poltekkes Kemenkes Yogyakarta, sistem yang mampu mengelola dan menggenerate sertifikat secara digital.</p>
                    <div class="mt-56 max-w-500-px text-start">
                        <span class="fw-semibold text-neutral-600 text-lg text-hover-neutral-600"> Masukkan Email Anda untuk mencari sertifikat!</span>
                        <form id="form" name="form" class="form-horizontal mt-16 d-flex gap-16 flex-sm-row flex-column">
                            @csrf
                            <input type="email" class="form-control text-start py-24 flex-grow-1" name="email" placeholder="contoh@email.com" required>
                            <button type="submit" class="btn btn-primary-600 px-24 flex-shrink-0 d-flex align-items-center justify-content-center gap-8" id="submit-button">
                                <i class="ri-search-line"></i> Cari
                            </button>
                        </form>
                        <div id="notification" class="mt-3 alert alert-danger" style="display: none;">
                            <i class="ri-error-warning-line"></i> Email tidak terdaftar dalam sistem.
                        </div>
                    </div>
                </div>

                <div class="col-lg-8" id="search-result-area" style="display: none;">
                    <div class="table-responsive"> 
                        
                    <div class="result-box" id="result-box" style="display: none;">
                        <h4 class="text-center mb-4">Hasil Pencarian Sertifikat</h4>
                        <div class="table-responsive" style="max-height: 585px; overflow-y: auto;">
                            <table class="table striped-table mb-0" style="position: sticky; top: 0; z-index: 1;">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Unduh</th>
                                    <th style="max-width: 20%">Nama Peserta</th>
                                    <th width="30%">Nama Sertifikat</th>
                                </tr>
                                </thead>
                                <tbody id="search-results">
                                    <!-- Results will be populated here -->
                                </tbody>
                            </table>
                            {{-- <table class="table table-striped table-bordered">
                                <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Peserta</th>
                                        <th>Nama Sertifikat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="search-results">
                                    <!-- Results will be populated here -->
                                </tbody>
                            </table> --}}
                        </div>
                    </div>
                    </div>
                    <div id="no-results" class="text-center">
                        {{-- <img src="{{ asset('wowdash/assets/images/maintenance.png') }}" alt="No Results" style="max-width: 300px;"> --}}
                        {{-- <p class="mt-3">Masukkan email Anda untuk mencari sertifikat.</p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery library js -->
<script src="{{ asset('wowdash/assets/js/lib/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap js -->
<script src="{{ asset('wowdash/assets/js/lib/bootstrap.bundle.min.js') }}"></script>

<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#submit-button').click(function (e) {
        e.preventDefault();

        const emailInput = $('input[name="email"]');
        const emailValue = emailInput.val().trim();

        if (emailValue === '') {
            $('#notification').html('<i class="ri-error-warning-line"></i> Email tidak boleh kosong atau hanya berisi spasi.').show();
            return; // Menghentikan eksekusi fungsi jika input tidak valid
        }

        // Hide notification when starting new search
        $('#notification').hide();
        $(this).html('<i class="ri-loader-4-line"></i> Mencari...').prop('disabled', true);

        let data = new FormData($("#form")[0]);

        $.ajax({
            data: data,
            url: "{{ route('cek') }}",
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (data) {
                $('#submit-button').html('<i class="ri-search-line"></i> Cari').prop('disabled', false);

                if (data && data.length > 0) {
                    $('#search-result-area').show();
                    $('#result-box').show();
                    $('#no-results').hide();
                    $('#notification').hide(); // Hide notification if results found

                    let resultsHtml = '';
                    data.forEach(function(item, index) {
                        resultsHtml += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>
                                    <form method="POST" action="/${item.name}" style="display: inline;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="peserta_id" value="${item.peserta_id}">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="ri-download-line"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>${item.name}</td>
                                <td>${item.file.substring(11).replace(/\.[^/.]+$/, "")}</td>
                            </tr>
                        `;
                    });

                    $('#search-results').html(resultsHtml);
                } else {
                    $('#search-result-area').hide();
                    $('#result-box').hide();
                    $('#no-results').show();
                    $('#search-results').html('');
                    $('#notification').show(); // Show notification if no results
                }
            },
            error: function (data) {
                $('#submit-button').html('<i class="ri-search-line"></i> Cari').prop('disabled', false);
                console.log('Error:', data);
                alert('Terjadi kesalahan saat mencari. Silakan coba lagi.');
                $('#search-result-area').hide();
                $('#result-box').hide();
                $('#no-results').show();
                $('#search-results').html('');
                $('#notification').hide(); // Hide notification on error
            }
        });
    });
});
</script>

</body>
</html>
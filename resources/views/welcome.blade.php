<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sertifikat Online Polkesyo</title>
        <link rel="icon" type="image/x-icon" href="{{asset('assets/assets/favicon.ico')}}" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('assets/css/styles.css') }}"rel="stylesheet" />

        <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
        {{-- datatables --}}
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>  
        <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script> 
    </head>
    <body>
        <!-- Background Video-->
        <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop"><source src="assets/title4.mp4" type="video/mp4" /></video>
        <!-- Masthead-->
        <div class="masthead">
            <div class="masthead-content text-white">
                <div class="container-fluid px-4 px-lg-0">
                    <h1 class="fst-italic lh-1 mb-4">SERPO 2.0 <br> Sertifikat Online Polkesyo</h1>
                    <p class="mb-5">Silahkan isi email anda.</p>
                    <form id="form" name="form" class="form-horizontal">
                        @csrf
                        <div class="input-group input-group-newsletter">
                            <input class="form-control" type="email" name="email" placeholder="Enter email address..." aria-label="Enter email address..." aria-describedby="submit-button" />

                            <button class="btn btn-primary" id="submit-button" type="submit" >Check</button>
                        </div>
                    </form>
                    <br>
                    <div id="lc">
                </div>
            </div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{asset ('assets/js/scripts.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
        <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#submit-button').click(function (e) {
            e.preventDefault();
            // $(this).html('Checking..');
            let data = new FormData($("#form")[0]);

                $.ajax({
                    data: data,
                    url: "{{ route('cek') }}",
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {

                        console.log(data);
                        var i = 0;
                        if (data !== null) {
                            var len = data.length;
                            for (; i < len; ) {
                                //eksekusi div
                            var element = document.createElement("div");
                            element.className = 'alert alert-secondary md'; //tambah class

                            my_form=document.createElement('FORM');
                            my_form.name='myForm';
                            my_form.method='POST';
                            my_form.action='{{route('download',['download'=>'pdf'])}}';
                            
                            var csrftoken=document.createElement("input");
                            csrftoken.setAttribute("type","hidden");
                            csrftoken.setAttribute("name","_token");
                            csrftoken.setAttribute("value","{{csrf_token()}}");
                            my_form.appendChild(csrftoken);

                            my_tb=document.createElement('INPUT');
                            my_tb.type= 'hidden';
                            my_tb.name='peserta_id';
                            my_tb.value=data[i].peserta_id;
                            my_tb.setAttribute('class',"btn btn-warning");
                            my_form.appendChild(my_tb);

                            my_tb=document.createElement('INPUT');
                            my_tb.type= 'submit';
                            my_tb.name='submit';
                            my_tb.value='Download';
                            my_tb.setAttribute('class',"btn btn-warning");
                            my_form.appendChild(my_tb);


                            //nama peserta
                            var h2 = document.createElement('h2')
                            h2.innerHTML= data[i].name;
                            //nama sertifikat 
                            var h3 = document.createElement('h3')
                            h3.innerHTML= data[i].file.substring(11).replace(/\.[^/.]+$/, "");

                            //membungkus perintah
                            element.appendChild(h2);
                            element.appendChild(h3);
                            // element.appendChild(f);
                            element.appendChild(my_form);
                            //eksekkusi menempelkan ke id div lc
                            document.getElementById('lc').appendChild(element);
                            i++;
                            }
                        }

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
        </script>
    </body>
</html>

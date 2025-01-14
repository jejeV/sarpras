@extends('mylayouts.main')

@section('tambahcss')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        .card-header h4 {
            font-size: 1.2rem !important
        }

        #upload-dialog{
            display: none !important;
        }

        #pdf-loader{
            display: none
        }

        #pdf-name{
            display: none !important;
        }

        #pdf-preview{
            display: none;
            width: 300px !important;
        }

        #upload-button{
            display: none !important;
        }

        #cancel-pdf{
            display: none !important;
        }

        .preview,
        .preview img{
            display: none;
        }

    </style>
@endsection

@section('container')
    {{-- @dd($data) --}}
    <div class="title pt-3">
        <h3 class="text-dark display-4 pl-3" style="font-size: 25px">Edit Ruang Kelas</h3>
    </div>

    <div class="form-edit pt-3">

        <form action="/usulan-bangunan/{{ $data->id }}" method="post">
            <div class="card pt-3" style="background-color: white; border-radius: 10px; ">


                {{-- --------------------------------------------- JUMLAH RUANG KELAS --------------------------------------------- --}}
                <div class="row input pl-5 mt-2">
                    <label class="col-2 mt-3">Jumlah Ruang Kelas</label>
                    <input type="number" class="col-9 form-control mt-2" placeholder="Masukan Jumlah Ruang"
                        value="{{ $data->jml_ruang }}" name="jml_ruang">
                </div>

                {{-- --------------------------------------------- LUAS LAHAN --------------------------------------------- --}}
                <div class="row input pl-5 mt-2">
                    <label class="col-2 mt-3">Luas Lahan</label>
                    <input type="number" class="col-9 form-control mt-2" placeholder="Masukan Luas Lahan"
                        value="{{ $data->jml_ruang }}" name="luas_lahan">
                </div>

                {{-- --------------------------------------------- GAMBAR LAHAN --------------------------------------------- --}}
                <div class="row input pl-5 mt-3" style="margin-top: 10px">
                    <label class="col-2 mt-1">Gambar Lahan</label>
                    <input type="file" id="gambar-lahan" nama="gambar[]" onchange="showPreview(event);">
                </div>
                <div class="container d-flex pl-5 mt-3">
                    @foreach ($fotos as $foto)
                        <div class="item col-lg-3 col-6 mb-3 container-image">
                            <div class="shadow-sm rounded border">
                                <button class="btn btn-danger shadow-sm button-hapus-image"
                                    style="position: absolute; right: 8px;" type="button"><i
                                        class="bi bi-trash-fill"></i></button>
                                <a href="{{ asset('storage/' . $foto->nama) }}" class="fancybox a-image"
                                    data-fancybox="gallery1">
                                    <img src="{{ asset('storage/' . $foto->nama) }}" class="rounded"
                                        style="object-fit: cover; width: 100%; aspect-ratio: 1/1;">
                                </a>
                            </div>
                        </div>
                    @endforeach
                    <div class="item col-lg-3 col-6 mb-3 container-image preview">
                        <div class="shadow-sm rounded border">
                            <a href="#" class="fancybox a-image"
                                data-fancybox="gallery1" id="href-preview">
                                <img id="file-ip-1-preview" class="rounded"
                                    style="object-fit: cover; width: 100%; aspect-ratio: 1/1;">
                            </a>
                        </div>
                    </div>
                </div>

                {{-- --------------------------------------------- PROPOSAL --------------------------------------------- --}}
                <div class="row input pl-5 mt-3" style="margin-top: 10px">
                    <label class="col-2 mt-1" value="{{ $data->proposal }}">Proposal</label>
                    <input type="file" id="proposal" name="proposal" value="{{ asset('storage/'.$data->proposal) }}">
                </div>
                <div class="container d-flex pl-5 mt-3">
                    <iframe class="iframe-proposal border border-rounded shadow-sm" src="{{ asset('storage/'.$data->proposal) }}" frameborder="0" width="300" height="400" style="display: block; border-radius: 10px !important;"></iframe>
                    <div id="pdf-loader">Loading Preview ..</div>
                    <div><canvas id="pdf-preview" class="border border-rounded shadow-sm" style="width: 300px !important; border-radius: 10px !important;"></canvas></div>
                </div>

                {{-- --------------------------------------------- SUBMIT --------------------------------------------- --}}
                <div class="pb-3 pl-5 mt-4">
                    <button type="button" class="btn text-white" style="background-color: #00a65b">Simpan</button>
                </div>

            </div>
        </form>
        
    </div>

    {{-- --------------------------------------------- ALERT --------------------------------------------- --}}
    <div class="container container-alert" style="position: fixed;right: 20px;bottom: 25px;width: auto"></div>
    <div id='alrt' style="fontWeight = 'bold'"></div>

    {{-- --------------------------------------------- HIDE --------------------------------------------- --}}
    {{-- <button id="upload-dialog">Choose PDF</button> hide --}}
    {{-- <span id="pdf-name"></span> hide --}}
    {{-- <button id="upload-button">Upload</button> hide --}}
    {{-- <button id="cancel-pdf">Cancel</button> hide --}}
@endsection

@section('tambahjs')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script src="/pdf.js"></script>
    <script src="/pdf.worker.js"></script>

    <script>
        function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.style.display = "block";
            document.querySelector('.preview').style.display = "block";
            document.getElementById("href-preview").href = src; 
            }
        }
    </script>

    <script>
        var iframe = document.querySelector('.iframe-proposal');
        
        document.querySelector("#proposal").addEventListener('change', function () {
            document.querySelector(".iframe-proposal").style.display = 'none';
        });
    </script>

    <script>
        document.getElementById('alrt').innerHTML = '<b>Please wait, Your download will start soon!!!</b>';

        setTimeout(function () {
            document.getElementById('alrt').innerHTML = '';
        }, 5000);
    </script>

    <script>
        var _PDF_DOC,
            _CANVAS = document.querySelector('#pdf-preview'),
            _OBJECT_URL;
        
        function showPDF(pdf_url) {
            PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
                _PDF_DOC = pdf_doc;
        
                // Show the first page
                showPage(1);
        
                // destroy previous object url
                URL.revokeObjectURL(_OBJECT_URL);
            }).catch(function(error) {
                // trigger Cancel on error
                document.querySelector("#cancel-pdf").click();
                
                // error reason
                alert(error.message);
            });;
        }
        
        function showPage(page_no) {
            // fetch the page
            _PDF_DOC.getPage(page_no).then(function(page) {
                // set the scale of viewport
                var scale_required = _CANVAS.width / page.getViewport(1).width;
        
                // get viewport of the page at required scale
                var viewport = page.getViewport(scale_required);
        
                // set canvas height
                _CANVAS.height = viewport.height;
        
                var renderContext = {
                    canvasContext: _CANVAS.getContext('2d'),
                    viewport: viewport
                };
                
                // render the page contents in the canvas
                page.render(renderContext).then(function() {
                    document.querySelector("#pdf-preview").style.display = 'inline-block';
                    document.querySelector("#pdf-loader").style.display = 'none';
                });
            });
        }
        
        
        /* Show Select File dialog */
        // document.querySelector("#upload-dialog").addEventListener('click', function() {
        //     document.querySelector("#proposal").click();
        // });
        
        /* Selected File has changed */
        document.querySelector("#proposal").addEventListener('change', function() {

            // user selected file
            var file = this.files[0];
        
            // allowed MIME types
            var mime_types = [ 'application/pdf' ];
            
            // Validate whether PDF
            if(mime_types.indexOf(file.type) == -1) {
                alert('Error : Incorrect file type');
                return;
            }
        
            // validate file size
            if(file.size > 10*1024*1024) {
                alert('Error : Exceeded size 10MB');
                return;
            }
        
            // validation is successful
        
            // hide upload dialog button
            // document.querySelector("#upload-dialog").style.display = 'none';
            
            // set name of the file
            // document.querySelector("#pdf-name").innerText = file.name;
            // document.querySelector("#pdf-name").style.display = 'inline-block';
        
            // show cancel and upload buttons now
            // document.querySelector("#cancel-pdf").style.display = 'inline-block';
            // document.querySelector("#upload-button").style.display = 'inline-block';
        
            // Show the PDF preview loader
            document.querySelector("#pdf-loader").style.display = 'inline-block';
        
            // object url of PDF 
            _OBJECT_URL = URL.createObjectURL(file)
        
            // send the object url of the pdf to the PDF preview function
            showPDF(_OBJECT_URL);
        });

        console.log(document.querySelector('.filePDF').value)

        window.addEventListener('load', function() {
            // document.querySelector('#pdf-file').value(document.querySelector('.filePDF').value)
        });
        
        /* Reset file input */
        document.querySelector("#cancel-pdf").addEventListener('click', function() {
            // show upload dialog button
            // document.querySelector("#upload-dialog").style.display = 'inline-block';
        
            // reset to no selection
            document.querySelector("#proposal").value = '';
        
            // hide elements that are not required
            // document.querySelector("#pdf-name").style.display = 'none';
            document.querySelector("#pdf-preview").style.display = 'none';
            document.querySelector("#pdf-loader").style.display = 'none';
            // document.querySelector("#cancel-pdf").style.display = 'none';
            // document.querySelector("#upload-button").style.display = 'none';
        });
        
        /* Upload file to server */
        document.querySelector("#upload-button").addEventListener('click', function() {
            // AJAX request to server
            alert('This will upload file to server');
        });
        
        </script>

    <script>
        const buttonHapusImage = document.querySelectorAll('.button-hapus-image');
        const aImage = document.querySelectorAll('.a-image');
        const containerImage = document.querySelectorAll('.container-image');
        const containerAlert = document.querySelector('.container-alert');

        let gambarHapus = [];

        buttonHapusImage.forEach((e, i) => {
            e.addEventListener('click', function() {

                let hasilConfirm = confirm('Apakah anda yakin akan mengapus gambar ini?');

                if (hasilConfirm == true) {
                    gambarHapus.push(aImage[i].getAttribute('href'));
                    containerImage[i].style.display = 'none';
                    containerAlert.innerHTML += `<div class="toast align-items-center show alert-ke${i}" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                Gambar berhasil dihapus
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>`;
                    const myTimeout = setTimeout(function() {
                        document.querySelector('.alert-ke' + i).style.display = 'none';
                    }, 3000);
                }

            })
        });
    </script>
@endsection

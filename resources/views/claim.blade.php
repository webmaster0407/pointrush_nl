<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <title>{{ $track->title }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <!-- @include('components.disclaimer') -->

    <div class="claim-title">
        <div></div>
        <h2>{{ $waypoint->title }}</h2>
        <div>
            <a href="{{ URL::to('/track/' . $track->id) }}"><i class="fas fa-times"></i></a>
        </div>
    </div>
    <div id="map-claim" data-claim-id="{{ $waypoint->id }}" data-claim-rad="{{ $waypoint->radius }}"
        data-claim-lat="{{ $waypoint->lat }}" data-claim-lon="{{ $waypoint->lon }}"></div>

    <div class="claim-form">
        <div class="card">
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" id="form-save-claim"
                    action="{{ route('frontend.track.saveClaim', ['track_id' => $track->id, 'waypoint_id' => $waypoint->id]) }}"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}

                    @if ($waypoint->upload_photo)
                        <input type="hidden" id="image_upload" name="image_upload">
                    @endif

                    @if ($waypoint->request_token)
                    <div class="form-group">
                        <label for="code">{{ __('basic.password') }}</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    @endif

                    @if ($waypoint->showrequest)
                        <div class="form-group">
                            <label
                                for="comment">{{ $waypoint->remarks_questions ?: __('basic.leave_comment') }}</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                    @endif
                </form>
                @if ($waypoint->upload_photo)
                    <div class="form-group">
                        <label for="upload_photo">{{ __('basic.upload_photo_claim') }}</label>
                        <input type="file" class="form-control" id="upload_photo" name="upload_photo" required
                            onchange="loadFile(event)" accept="image/*">
                    </div>
                    <div class="text-center" id="container-image-photo">
                        <img src="" alt="" id="show-image-photo" class="mt-4" style="display: none;width: 100%;">
                    </div>
                @endif

                <div class="text-center mt-4">
                    <button type="button" onclick="save()"
                        class="btn btn-primary mb-2 claim-save">{{ __('basic.send') }}</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        var baseUrl = "{{ url('/') }}"

        const MAX_WIDTH = 1024;
        const MAX_HEIGHT = 768;
        const MIME_TYPE = "image/jpeg";
        const QUALITY = 0.7;

        function save() {
            $("#form-save-claim").trigger('submit');
        }

        function loadFile(ev) {
            const file = ev.target.files[0];
            const blobURL = URL.createObjectURL(file);
            document.getElementById('show-image-photo').src = blobURL;
            $("#show-image-photo").slideDown();
            const img = new Image();
            img.src = blobURL;
            img.onerror = function() {
                URL.revokeObjectURL(this.src);
                console.log("Cannot load image");
            };
            img.onload = function() {
                URL.revokeObjectURL(this.src);
                const [newWidth, newHeight] = calculateSize(img, MAX_WIDTH, MAX_HEIGHT);
                const canvas = document.createElement("canvas");
                canvas.width = newWidth;
                canvas.height = newHeight;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, newWidth, newHeight);
                canvas.toBlob(
                    (blob) => {
                        // $(".info-photo").remove();
                        // displayInfo('Original file', file);
                        // displayInfo('Compressed file', blob);

                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = function() {
                            $("#image_upload").val(reader.result);
                        }
                    },
                    MIME_TYPE,
                    QUALITY
                );
                // canvas.style.width = '100%';
                // $("#show-image-photo").html(canvas);
            };
        };

        function calculateSize(img, maxWidth, maxHeight) {
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = Math.round((width * maxHeight) / height);
                    height = maxHeight;
                }
            }
            return [width, height];
        }

        function displayInfo(label, file) {
            const p = document.createElement('div');
            p.classList.add('info-photo');
            p.innerText = `${label} - ${readableBytes(file.size)}`;
            $('#container-image-photo').append(p);
            // console.log(`${label} - ${readableBytes(file.size)}`);
        }

        function readableBytes(bytes) {
            const i = Math.floor(Math.log(bytes) / Math.log(1024)),
                sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
        }
    </script>
    <script src="{{ asset('js/claim.js') }}" defer></script>

</body>

</html>

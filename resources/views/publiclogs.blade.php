@extends('layouts.app_public')

@section('content')


    <div id="logs_data" class="mt-10 mb-10">
        <div class="container">

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="row my-3">
                <div class="col-md-12 d-flex flex-row justify-content-between">
                    <div class="col-md-6 d-flex flex-row ">
                        <input type="hidden" name="" id="selecttrackpicker" value="{{ $data['tracks']['id'] }}">
                        <div class="input-group ">
                            <input type="text" id="date_time_value" class="form-control" placeholder="DD:MM:YYYY hh:mm"
                                aria-label="DD:MM:YYYY hh:mm" aria-describedby="basic-addon2">
                            <div class="input-group-append" id="datetime_logs">
                                <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar-alt"
                                        data-type="start"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="logs-table-custom-public" style="position:relative;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Time Stamp</i></th>
                                <th scope="col">Track</i></th>
                                <th scope="col">Waypoint</i></th>
                                <th scope="col">Remark</i></th>
                                <th scope="col">Photo</i></th>

                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
                <div class="loader" style="display: none ;top: 0;">
                    <img src="{{ asset('images/loader2.svg') }}" />
                </div>
            </div>


        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modal-img">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="show-img-large" src="" style="width: 100%" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="datetimeModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('basic.date_and_time') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="overflow:hidden;">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="datetimepicker_logs"></div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">

                        </script>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('basic.close') }}</button>

                </div>
            </div>
        </div>
    </div>


@section('scripts')
    <script>
        var baseUrl = "{{ url('/') }}"
        var initialLogdata = @json($data);
        // var data = data

        function openPhoto(url) {
            $("#show-img-large").prop('src', url);
            $("#modal-img").modal('show');
        }
    </script>
    <script src="{{ asset('js/claim.js') }}" defer></script>
@endsection
@endsection

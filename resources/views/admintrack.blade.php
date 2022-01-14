@extends('layouts.app')

@section('title', __('basic.edit_track'))
@section('url', route('admin.track.point', $id))
@section('caption', __('basic.content'))

@section('content')
<!--Begin Comm100 Live Chat Code-->
<div id="comm100-button-c705cbec-ab9b-4026-b343-c5dde344c0ad"></div>
<script type="text/javascript">
  var Comm100API=Comm100API||{};(function(t){function e(e){var a=document.createElement("script"),c=document.getElementsByTagName("script")[0];a.type="text/javascript",a.async=!0,a.src=e+t.site_id,c.parentNode.insertBefore(a,c)}t.chat_buttons=t.chat_buttons||[],t.chat_buttons.push({code_plan:"c705cbec-ab9b-4026-b343-c5dde344c0ad",div_id:"comm100-button-c705cbec-ab9b-4026-b343-c5dde344c0ad"}),t.site_id=50100085,t.main_code_plan="c705cbec-ab9b-4026-b343-c5dde344c0ad",e("https://vue.comm100.com/livechat.ashx?siteId="),setTimeout(function(){t.loaded||e("https://standby.comm100vue.com/livechat.ashx?siteId=")},5e3)})(Comm100API||{})
</script>
<!--End Comm100 Live Chat Code-->

    {{-- <div class="container mb-4">
        <h4>
            Track: <strong>{{ $track->title }}</strong>
        </h4>
    </div> --}}

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="container-map">
        <div id="map"></div>
    </div>
    <!--<div id="map" style="min-height:400px;height:50%;width:60%"></div> -->



    <div style="height:50%;overflow-y: scroll;">
        <div class="container">

            <div class="row my-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button id="add" class="btn btn-default">{{ __('basic.add') }}</button>
                        </span>
                        <span class="input-group-btn">
                            <button id="save" class="btn btn-default">{{ __('basic.save') }}</button>
                        </span>
                    </div>
                </div>
                {{-- <a href="javascript:;" class="btn btn-primary duplicate-track mr-4"
                    data-id="{{ $id }}"
                    data-url={{ route('track.duplicate') }}>
                    <i class="fa fa-clone mr-2"></i>{{ __('basic.duplicate') }} Track
                </a> --}}
                {{-- <div class="col-md-1">
                    <button href="/admin/help" target="_blank" class="btn btn-default"
                        onClick="window.open('/admin/help', '_blank', 'toolbar=0,location=0,menubar=0');">{{ __('basic.help') }}</button>
                </div> --}}
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <a href="{{ URL::to('/track/' . $id) }}" class="btn btn-default set-tooltip" id="link-button"
                                data-toggle="tooltip" data-placement="bottom" title="{{ __('basic.goto') }}"
                                target="_blank">
                                <i class="fas fa-link"></i>
                            </a>
                        </span>
                        <input type="text" class="form-control" value="{{ URL::to('/track/' . $id) }}"
                            placeholder="Some path" id="copy-input">
                        <span class="input-group-btn">
                            <a href="javascript:;" class="btn btn-default set-tooltip" id="copy-button"
                                data-toggle="tooltip" data-placement="bottom" title="{{ __('basic.copy') }}">
                                <i class="fas fa-copy"></i>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a href="{{ URL::to('admin/log/track/' . $id) }}" class="btn btn-default set-tooltip"
                                id="track-log" data-toggle="tooltip" data-placement="bottom"
                                title="{{ __('basic.claims') }}">
                                <i class="fas fa-list"></i>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a href="{{ route('admin.track.edit', ['id' => $id]) }}" class="btn btn-default set-tooltip"
                                data-toggle="tooltip" data-placement="bottom" title="{{ __('basic.track_settings') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a href="javascript:;" class="btn btn-default set-tooltip"
                                onClick="window.open('/admin/help', '_blank', 'toolbar=0,location=0,menubar=0');"
                                data-toggle="tooltip" data-placement="bottom" title="{{ __('basic.help') }}">
                                <i class="fas fa-question"></i>
                            </a>
                        </span>
                        <span class="input-group-btn">
                            <a href="javascript:;" class="btn btn-default set-tooltip" data-toggle="tooltip"
                                data-placement="bottom" title="{{ __('basic.count_visitor') }}">
                                <span class="badge badge-pill badge-success">
                                    {{ $track->visitor }}
                                </span>
                            </a>
                        </span>
                    </div>
                </div>

            </div>

            <div id="form-errors">
            </div>
            <div id="data-table">

            </div>

            <div id="data-table-custom">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">{{ __('basic.title') }}</th>
                                <th scope="col">{{ __('basic.start') }}</th>
                                <th scope="col">{{ __('basic.stop') }}</th>
                                <th scope="col">{{ __('basic.lat') }}</th>
                                <th scope="col">{{ __('basic.lon') }}</th>
                                <th scope="col">{{ __('basic.radius') }}</th>
                                <th scope="col">{{ __('basic.show_timer') }}</th>
                                <th scope="col">{{ __('basic.show_title') }}</th>
                                <th scope="col">Claimable</th>
                                <!-- <th scope="col">Remarks</th> -->
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="myModal">
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
                                    <div id="datetimepicker13"></div>
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

    <div id="disclaimer">
        <div class="header">
            <h3 class="left">Disclaimer</h3>
            <span class="right"><i class="fas fa-times disc"></i></span>
        </div>
        <div class="text">


            Voor vragen over de Admin pagina, vraag Arjan</BR>
            Hij is te bereiken op 0643 23 13 03
        </div>
    </div>
    <div class="modal fade" id="modal-color" tabindex="-1" role="dialog" aria-labelledby="modal-color" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-modal-color">{{ __('basic.color_radius') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" style="display: none">
                        {{ __('basic.color_save_success') }}
                    </div>
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="color" value="">
                    <p id="note-color">
                        <small>{{ __('basic.color_picker_note') }}</small>
                    </p>
                    <div id="color-radius"></div>
                </div>
                <div class="modal-footer">
                    <div class="row" style="width: 100%">
                        <div class="col-md-4">
                            <div style="" class="text-left">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="transparant" class="custom-control-input" id="transparant">
                                    <label class="custom-control-label" for="transparant">Transparant</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 text-right">
                            <button type="button" class="btn btn-default" id="reset-color">Reset</button>
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ __('basic.close') }}</button>
                            <button type="button" class="btn btn-primary"
                                id="save-modal-color">{{ __('basic.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#claimFormModal">
        Launch demo modal
    </button> --}}

    <!-- Modal -->
    <div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="editFormModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Track</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="track-title">*Title</label>
                            <input type="text" class="form-control" id="track-title" placeholder="title">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Lat</label>
                                    <input type="number" class="form-control" id="track-lat" placeholder="Lat">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Lon</label>
                                    <input type="number" class="form-control" id="track-lon" placeholder="Lon">
                                    <small id="error-long" class="text-danger" style="font-size: 8pt;display: none;">This
                                        value cannot more than 180 and less than -180
                                        degree</small>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label>Radius</label>
                            <input type="number" class="form-control" id="track-radius" placeholder="Radius">
                        </div>
                        <input type="hidden" name="track-id" id="track-id">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('basic.close') }}</button>
                    <button type="button" class="btn btn-primary" id="saveTrackData">Update</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="claimFormModal" tabindex="-1" role="dialog" aria-labelledby="claimFormModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title-claim-setting" data-title="{{ __('basic.claim_settings') }}"="">
                        {{ __('basic.claim_settings') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="claim-next-point">{{ __('basic.next_follow_point') }}</label>
                            <select class="form-control" id="claim-next-point" name="claim-next-point" multiple>

                            </select>
                            <span class="text-danger claim-followUp-error"
                                style="display:none">{{ __('messages.required') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input " id="request_token">
                                <label class="custom-control-label"
                                    for="request_token">{{ __('basic.requset_claim') }}</label>
                            </div>
                        </div>
                        <div class="form-group" id="container-token">
                            <input type="text" class="form-control" id="claim-code"
                                placeholder="{{ __('basic.claim_code') }}">
                            <span class="text-danger claim-code-error"
                                style="display:none">{{ __('messages.required') }}</span>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input " id="remarks">
                                <label class="custom-control-label" for="remarks">{{ __('basic.remarks') }}</label>
                            </div>
                        </div>
                        <div class="form-group" id="container-remarks">
                            {{-- <label for="claim-question">{{ __('basic.remarks_questions') }}</label> --}}
                            <input type="text" class="form-control" id="claim-question"
                                placeholder="{{ __('basic.leave_comment') }}">
                        </div>
                        <!-- <div class="form-group">
                                                                                                                                                                    <label for="comment"></label>
                                                                                                                                                                    <input type="checkbox" class="form-control" id="remarks" name="remarks" required> {{ __('basic.remarks') }}
                                                                                                                                                                </div> -->

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input " id="upload_photo">
                                <label class="custom-control-label"
                                    for="upload_photo">{{ __('basic.upload_photo') }}</label>
                            </div>
                        </div>

                        <input type="hidden" name="track-id" id="track-id">
                        <input type="hidden" name="point-id" id="point-id">

                    </form>
                </div>
                <div id="form-errors">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('basic.close') }}</button>
                    <button type="button" class="btn btn-primary" id="saveClaimData">{{ __('basic.save') }}</button>
                </div>
                <div class="loader" style="display: none">
                    <img src="{{ asset('images/loader2.svg') }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddFormModal" tabindex="-1" role="dialog" aria-labelledby="AddFormModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('basic.add_waypoint') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="track-title">{{ __('basic.title') }}</label>
                            <input type="text" class="form-control" id="track-title" placeholder="title">
                            <span class="text-danger add-title-error"
                                style="display:none">{{ __('messages.required') }}</span>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ __('basic.lat') }}</label>
                                    <input type="number" class="form-control" id="track-lat" placeholder="Lat">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ __('basic.lon') }}</label>
                                    <input type="number" class="form-control" id="track-lon" placeholder="Lon">
                                    <small id="error-long" class="text-danger" style="font-size: 8pt;display: none;">This
                                        value cannot more than 180 and less than -180
                                        degree</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('basic.radius') }}</label>
                            <input type="number" class="form-control" id="track-radius" placeholder="Radius">
                        </div>
                        <input type="hidden" name="track-id" id="track-id">

                    </form>
                    <div id="add-form-errors" class="form-errors">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('basic.close') }}</button>
                    <button type="button" class="btn btn-primary" id="addTrackData">{{ __('basic.add') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-clone-point" tabindex="-1" role="dialog" aria-labelledby="ModalClonePoint"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('basic.duplicate') }} Point</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="text-center">
                        {{ __('basic.duplicate_point') }} <input type="number" id="count-of-duplicates"
                            class="form-control"> {{ __('basic.times') }}
                    </form>

                </div>
                <div class="alert alert-success form-success" style="display: none">
                    {{ __('basic.point_copied') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('basic.close') }}</button>
                    <button type="button" class="btn btn-primary"
                        id="action-duplicates">{{ __('basic.duplicate') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-duplicate-track" tabindex="-1" role="dialog" aria-labelledby="ModalCloneTrack"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('basic.duplicate') }} Track</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="text-center" id="form-duplicate-track">
                        <input type="hidden" name="track_id" class="form-control">
                        {{ __('basic.duplicate_track') }} <input type="number" name="number" class="form-control">
                        {{ __('basic.times') }}
                    </form>

                </div>
                <div class="alert alert-danger form-error" style="display: none">
                    {{ __('basic.number_must_greater_0') }}
                </div>
                <div class="alert alert-success form-success" style="display: none">
                    {{ __('basic.track_copied') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('basic.close') }}</button>
                    <button type="button" class="btn btn-primary"
                        id="action-duplicate-track">{{ __('basic.duplicate') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

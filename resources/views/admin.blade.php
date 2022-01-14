@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row d-xl-none">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong> {{ __('messages.warning') }}! </strong> {{ __('messages.mobile_dislaimer') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <div class="row">
            @if (Auth::user()->roles == 0)
                <a href="/admin/adduser" class="btn btn-default">{{ __('basic.add_user') }}</a>
            @endif
            <a href="/admin/addtrack" class="btn btn-default ml-2">{{ __('basic.add_track') }}</a>
            <div class="text-right">
                <a href="/admin/log" class="btn btn-default ml-2">{{ __('basic.logs') }}</a>

            </div>
        </div>

        @foreach ($users as $u)
            <div class="row justify-content-center pt-3">
                <div class="col-md-12">
                    <div class="card{{ $u->id == 1 ? ' border-primary ' : '' }}">

                        <div class="card-header d-flex justify-content-between">{{ $u->email }}
                            @if (Auth::user()->roles == 0 && $u->id != 1)
                                <button class="btn btn-sm btn-danger ml-2 remove-user" data-uid="{{ $u->id }}"
                                    data-email="{{ $u->email }}">{{ __('basic.remove') }}</button>
                            @endif
                        </div>

                        <div class="card-body">


                            <ul class="list-group">



                                @if (sizeof($u->tracks))
                                    @foreach ($u->tracks as $track)

                                        <li class="list-group-item list-group-item-action d-flex justify-content-between tracktitle"
                                            id="track-{{ $track->id }}">
                                            <div>

                                                #{{ $track->id }} {{ $track->title }}
                                            </div>
                                            <div>

                                                <a href="/admin/track/{{ $track->id }}"
                                                    class="btn btn-sm btn-default edit-track">{{ __('basic.edit') }}</a>
                                                {{-- <a href="javascript:;" class="btn btn-sm btn-primary duplicate-track"
                                                    data-id="{{ $track->id }}"
                                                    data-url={{ route('track.duplicate') }}>
                                                    <i class="fa fa-clone mr-2"></i>{{ __('basic.duplicate') }}
                                                </a> --}}
                                                <a href="/admin/edittrack/{{ $track->id }}"
                                                    class="btn btn-default edit-tracktitle btn-sm set-tooltip"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="{{ __('basic.track_settings') }}"><i
                                                        class="fas fa-edit"></i></a>
                                                <button class="btn btn-sm btn-default ml-2 remove-track  set-tooltip"
                                                    data-toggle="tooltip" data-placement="bottom"
                                                    title="{{ __('basic.remove') }}" data-tid="{{ $track->id }}"
                                                    data-title="{{ $track->title }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="btn btn-sm btn-default set-tooltip ml-2" data-toggle="tooltip"
                                                    data-placement="bottom" data-title="{{ __('basic.count_visitor') }}">
                                                    <span class="badge badge-pill badge-success">
                                                        {{ $track->visitor }}
                                                    </span>
                                                </button>
                                            </div>
                                        </li>
                                        <li class="list-group-item list-group-item-action disabled trackcontent"
                                            id="trackc-{{ $track->id }}">
                                            <p>{{ __('basic.loading') }}</p>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item list-group-item-action text-center disabled">
                                        {{ __('basic.nothing_to_show') }}
                                    </li>
                                @endif


                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        id="action-duplicate-track">{{ __('basic.duplicate') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

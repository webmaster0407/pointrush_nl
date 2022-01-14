@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success my-4">
                        {{ session('status') }}
                    </div>
                @endif

                <div id="alert-success" class="alert alert-success" style="display: none">
                    {{ __('basic.save_successfully') }}
                </div>

                <div class="card">
                    <div class="card-header">{{ __('basic.game_site_setting') }}</div>

                    <div class="card-body">
                        <p> <i>
                                {{ __('basic.game_site_setting_subtitle') }}</i>
                        </p>
                        <form method="POST" action="{{ route('edittrack') }}" id="form-game-settting">
                            @csrf
                            <input type="hidden" name="id" value="{{ $track->id }}" />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input " id="hide-menu-bar" @if ($track->hide_menu_bar) checked @endif>
                                        <label class="custom-control-label" for="hide-menu-bar"
                                            style="color: gray">{{ __('basic.hide_menu_bar_in_front_end') }}</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('edittrack') }}" id="form-show_log_public">
                            @csrf
                            <input type="hidden" name="id" value="{{ $track->id }}" />
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input " id="show_log_public" @if ($track->show_log_public) checked @endif>
                                        <label class="custom-control-label"
                                            for="show_log_public">{{ __('basic.show_log_public') }} <a
                                                href="{{ route('frontend.log.log', ['track_id' => $track->id]) }}"
                                                id="link-log-public" @if (!$track->show_log_public) style="display:none" @endif>({{ route('frontend.log.log', ['track_id' => $track->id]) }})</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">{{ __('basic.back_end_setting') }}</div>

                    <div class="card-body">
                        <p> <i>
                                {{ __('basic.back_end_setting_subtitle') }}</i>
                        </p>
                        <form method="POST" action="{{ route('edittrack') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $track->id }}" />
                            <div class="row">
                                <div class="col-md-12 d-flex align-items-center">
                                    {{ __('basic.title') }}
                                    <input id="title" type="text"
                                        class="form-control mx-2 @error('name') is-invalid @enderror" name="title"
                                        value="{{ $track->title }}" required autocomplete="title" autofocus
                                        style="width: 300px">
                                    <button type="submit" class="btn btn-success ml-4">
                                        {{ __('basic.change_track_name') }}
                                    </button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">{{ __('basic.maintenance_setting') }}</div>
                    <div class="card-body">
                        <p> <i>
                                {{ __('basic.maintenance_setting_subtitle') }}</i>
                        </p>
                        <form method="POST" action="{{ route('track.duplicate') }}">
                            @csrf
                            <input type="hidden" name="track_id" class="form-control" value="{{ $track->id }}">
                            <div class="row">
                                <div class="col-md-12 d-flex align-items-center">
                                    {{ __('basic.duplicate_track') }}
                                    <input type="number" id="number_duplicate" name="number_duplicate"
                                        class="form-control mx-2 @error('number_duplicate') is-invalid @enderror"
                                        style="width: 70px">
                                    {{ __('basic.times') }}
                                    <button type="submit"
                                        class="btn btn-success ml-4">{{ __('basic.duplicate') }}</button>
                                </div>
                                <div class="col-md-12">
                                    @error('number_duplicate')
                                        <small class="text-danger text-muted">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('track.move') }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="track_id" class="form-control" value="{{ $track->id }}">
                            <div class="row">
                                <div class="col-md-12 d-flex align-items-center">
                                    {{ __('basic.move_track') }}
                                    <input type="number" name="number_interval" id="number_interval"
                                        class="form-control ml-2  @error('number_interval') is-invalid @enderror"
                                        style="width: 70px">
                                    <select name="interval" class="form-control mx-2" style="width: 110px">
                                        <option value="minutes">{{ __('basic.minutes') }}</option>
                                        <option value="hours">{{ __('basic.hours') }}</option>
                                        <option value="days">{{ __('basic.days') }}</option>
                                        <option value="weeks">{{ __('basic.weeks') }}</option>
                                        <option value="years">{{ __('basic.years') }}</option>
                                    </select>
                                    <button type="submit" class="btn btn-success ml-4">{{ __('basic.move') }}</button>
                                </div>
                                <div class="col-md-12">
                                    @error('number_interval')
                                        <small class="text-danger text-muted">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

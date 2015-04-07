@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @parent
    {{{ trans("action.{$mode}") }}} {{{ trans('stevebauman/localization::translations/common.title_with_locale', array('locale' => $locale->display_name)) }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('selectize', 'selectize/css/selectize.bootstrap3.css', 'styles') }}
{{ Asset::queue('redactor', 'redactor/css/redactor.css', 'styles') }}

{{ Asset::queue('slugify', 'platform/js/slugify.js', 'jquery') }}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}
{{ Asset::queue('selectize', 'selectize/js/selectize.js', 'jquery') }}
{{ Asset::queue('redactor', 'redactor/js/redactor.min.js', 'jquery') }}

{{-- Page --}}
@section('page')
    <section class="panel panel-default">

        {{-- Form --}}
        <form id="content-form" action="{{ request()->fullUrl() }}" role="form" method="post" accept-char="UTF-8" autocomplete="off" data-parsley-validate>

            {{-- Form: CSRF Token --}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <header class="panel-heading">

                <nav class="navbar navbar-default navbar-actions">

                    <div class="container-fluid">

                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                            <a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.localization.locales.translations.index', array($locale->id)) }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
                                <i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
                            </a>

                            <span class="navbar-brand">{{{ trans("action.{$mode}") }}}</span>
                        </div>

                        {{-- Form: Actions --}}
                        <div class="collapse navbar-collapse" id="actions">

                            <ul class="nav navbar-nav navbar-right">

                                @if ($locale->exists)
                                    <li>
                                        <a href="{{ route('admin.localization.locales.translations.delete', $locale->id, $translation->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
                                            <i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
                                        <i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
                                    </button>
                                </li>

                            </ul>

                        </div>

                    </div>

                </nav>

            </header>

            <div class="panel-body">

                <div class="row">

                    <div class="col-md-12">

                        <fieldset>

                            <legend>{{{ trans('stevebauman/localization::translations/model.general.legend') }}}</legend>

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">

                                        <label class="control-label" for="translation">
                                            {{{ $translation->locale->display_name }}} {{ trans('stevebauman/localization::translations/common.title_singular') }}
                                        </label>

                                        <textarea class="form-control" disabled>{{{ $translation->translation }}}</textarea>

                                    </div>
                                </div>

                            </div>

                            @if(count($translations) > 0)

                                @foreach($translations as $translation)
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <label class="control-label" for="translation">
                                                    {{{ $translation->locale->display_name }}} {{ trans('stevebauman/localization::translations/common.title_singular') }}
                                                </label>

                                                <textarea class="form-control">{{{ $translation->translation }}}</textarea>

                                            </div>
                                        </div>

                                    </div>
                                @endforeach

                            @else

                            @endif
                        </fieldset>

                    </div>
                </div>
            </div>

        </form>

    </section>
@stop

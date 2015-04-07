@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @parent
    {{{ trans("action.{$mode}") }}} {{{ trans('stevebauman/localization::locales/common.title') }}}
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
        <form id="content-form" action="{{ request()->fullUrl() }}" role="form" method="post" accept-char="UTF-8"
              autocomplete="off" data-parsley-validate>

            {{-- Form: CSRF Token --}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <header class="panel-heading">

                <nav class="navbar navbar-default navbar-actions">

                    <div class="container-fluid">

                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#actions">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                            <a class="btn btn-navbar-cancel navbar-btn pull-left tip"
                               href="{{ route('admin.localization.locales.index') }}" data-toggle="tooltip"
                               data-original-title="{{{ trans('action.cancel') }}}">
                                <i class="fa fa-reply"></i> <span
                                        class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
                            </a>

                            <span class="navbar-brand">{{{ trans("action.{$mode}") }}}
                                <small>{{{ $locale->exists ? $locale->name : null }}}</small></span>
                        </div>

                        {{-- Form: Actions --}}
                        <div class="collapse navbar-collapse" id="actions">

                            <ul class="nav navbar-nav navbar-right">

                                @if ($locale->exists)
                                    <li>
                                        <a href="{{ route('admin.localization.locales.translations.index', array($locale->id)) }}">
                                            {{{ trans('stevebauman/localization::locales/common.view-translations') }}}
                                            <i class="fa fa-exchange"></i>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.localization.locales.delete', $locale->id) }}"
                                           class="tip" data-action-delete data-toggle="tooltip"
                                           data-original-title="{{{ trans('action.delete') }}}" type="delete">
                                            <i class="fa fa-trash-o"></i> <span
                                                    class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <button class="btn btn-primary navbar-btn" data-toggle="tooltip"
                                            data-original-title="{{{ trans('action.save') }}}">
                                        <i class="fa fa-save"></i> <span
                                                class="visible-xs-inline">{{{ trans('action.save') }}}</span>
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

                            <legend>{{{ trans('stevebauman/localization::locales/model.general.legend') }}}</legend>

                            <div class="row">

                                <div class="col-md-2">

                                    {{-- Name --}}
                                    <div class="form-group{{ Alert::onForm('code', ' has-error') }}">

                                        <label class="control-label" for="name">
                                            <i class="fa fa-info-circle" data-toggle="popover"
                                               data-content="{{{ trans('stevebauman/localization::locales/model.general.code_help') }}}"></i>
                                            {{{ trans('stevebauman/localization::locales/model.general.code') }}}
                                        </label>

                                        <select class="form-control" name="code" id="code">
                                            <option value="">Select a code...</option>
                                            @foreach ($locales as $code => $availableLocale)
                                                <option value="{{ $code }}"{{ request()->old('code', $locale->code) == $code ? ' selected="selected"' : null}}>{{ $code }}</option>
                                            @endforeach
                                        </select>

                                        <span class="help-block">{{{ Alert::onForm('code') }}}</span>

                                    </div>

                                </div>

                                <div class="col-md-2">

                                    {{-- Language Code --}}
                                    <div class="form-group{{ Alert::onForm('lang_code', ' has-error') }}">

                                        <label class="control-label" for="lang_code">
                                            <i class="fa fa-info-circle" data-toggle="popover"
                                               data-content="{{{ trans('stevebauman/localization::locales/model.general.lang_code_help') }}}"></i>
                                            {{{ trans('stevebauman/localization::locales/model.general.lang_code') }}}
                                        </label>

                                        <input type="text" class="form-control" name="lang_code" id="lang_code"
                                               placeholder="{{{ trans('stevebauman/localization::locales/model.general.lang_code_help') }}}"
                                               value="{{{ request()->old('lang_code', $locale->lang_code) }}}"
                                               data-parsley-trigger="change">

                                        <span class="help-block">{{{ Alert::onForm('lang_code') }}}</span>
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Name --}}
                                    <div class="form-group{{ Alert::onForm('name', ' has-error') }}">

                                        <label class="control-label" for="name">
                                            <i class="fa fa-info-circle" data-toggle="popover"
                                               data-content="{{{ trans('stevebauman/localization::locales/model.general.name_help') }}}"></i>
                                            {{{ trans('stevebauman/localization::locales/model.general.name') }}}
                                        </label>

                                        <select class="form-control" name="name" id="name">
                                            <option value="">Select a name...</option>
                                            @foreach ($locales as $availableLocale)
                                                <option value="{{ $availableLocale }}"{{ request()->old('name', $locale->name) == $availableLocale ? ' selected="selected"' : null}}>{{ $availableLocale }}</option>
                                            @endforeach
                                        </select>

                                        <span class="help-block">{{{ Alert::onForm('name') }}}</span>

                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Display Name --}}
                                    <div class="form-group{{ Alert::onForm('display_name', ' has-error') }}">

                                        <label class="control-label" for="display_name">
                                            <i class="fa fa-info-circle" data-toggle="popover"
                                               data-content="{{{ trans('stevebauman/localization::locales/model.general.display_name_help') }}}"></i>
                                            {{{ trans('stevebauman/localization::locales/model.general.display_name') }}}
                                        </label>

                                        <input type="text" class="form-control" name="display_name" id="display_name"
                                               placeholder="{{{ trans('stevebauman/localization::locales/model.general.display_name_help') }}}"
                                               value="{{{ request()->old('display_name', $locale->display_name) }}}"
                                               data-parsley-trigger="change">

                                        <span class="help-block">{{{ Alert::onForm('display_name') }}}</span>
                                    </div>

                                </div>

                            </div>

                        </fieldset>

                    </div>
                </div>
            </div>

        </form>

    </section>
@stop

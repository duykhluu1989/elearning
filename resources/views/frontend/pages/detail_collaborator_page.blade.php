@extends('frontend.layouts.main')

@section('og_description', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'short_description'))

@section('og_image', $page->image)

@section('page_heading', \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name')])

    <section class="banner_cvtPP">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="display_table mh400">
                        <div class="table_content">
                            <h1>@lang('theme.become_collaborator')</h1>
                            <p>@lang('theme.collaborator_slogan')</p>

                            @if(auth()->guest() || empty(auth()->user()->collaboratorInformation))
                                <a href="javascript:void(0)" class="btn btn-lg btnRed" id="CollaboratorSignUp">@lang('theme.sign_up')</a>
                                {{ csrf_field() }}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <ul class="list_navLeft">

                                @foreach($sameGroupPages as $sameGroupPage)
                                    @if($sameGroupPage->id == $page->id)
                                        <li class="active"><a href="javascript:void(0)">{{ \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'name') }}</a></li>
                                    @else
                                        <li><a href="{{ action('Frontend\PageController@detailPage', ['id' => $sameGroupPage->id, 'slug' => \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'slug')]) }}">{{ \App\Libraries\Helpers\Utility::getValueByLocale($sameGroupPage, 'name') }}</a></li>
                                    @endif
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <h2>{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}</h2>

                            @if(!empty($page->image))
                                <img src="{{ $page->image }}" alt="{{ \App\Libraries\Helpers\Utility::getValueByLocale($page, 'name') }}" class="img-responsive w100p mb30">
                            @endif

                            <?php
                            echo \App\Libraries\Helpers\Utility::getValueByLocale($page, 'content');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @if(auth()->guest() || empty(auth()->user()->collaboratorInformation))
        @if(auth()->guest())
            <div id="CollaboratorSignUpModal" class="modal fade modal_general" data-backdrop="static" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title text-center">@lang('theme.sign_up') <span class="logo_small"><img src="{{ asset('themes/images/logo_small.png') }}" alt="Logo" class="img-responsive"></span></h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ action('Frontend\UserController@registerCollaborator') }}" method="POST" role="form" class="frm_dangky" id="CollaboratorSignUpForm">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="first_name" placeholder="* @lang('theme.first_name')" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="last_name" placeholder="@lang('theme.last_name')">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" placeholder="* Email" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="* @lang('theme.password')" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bank" placeholder="@lang('theme.bank')">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bank_holder" placeholder="@lang('theme.bank_name')">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bank_number" placeholder="@lang('theme.bank_number')">
                                </div>
                                <button type="submit" class="btn btn-block btnDangky">@lang('theme.sign_up')</button>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div id="CollaboratorSignUpModal" class="modal fade modal_general" data-backdrop="static" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title text-center">@lang('theme.sign_up') <span class="logo_small"><img src="{{ asset('themes/images/logo_small.png') }}" alt="Logo" class="img-responsive"></span></h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ action('Frontend\UserController@registerCollaborator') }}" method="POST" role="form" class="frm_dangky" id="CollaboratorSignUpForm">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bank" placeholder="@lang('theme.bank')">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bank_holder" placeholder="@lang('theme.bank_name')">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="bank_number" placeholder="@lang('theme.bank_number')">
                                </div>
                                <button type="submit" class="btn btn-block btnDangky">@lang('theme.sign_up')</button>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @push('scripts')
            <script type="text/javascript">
                $('#CollaboratorSignUp').click(function() {
                    $('#CollaboratorSignUpModal').modal('show');
                });

                $('#CollaboratorSignUpForm').submit(function(e) {
                    e.preventDefault();

                    var formElem = $(this);

                    formElem.find('input').each(function() {
                        $(this).parent().removeClass('has-error').find('span[class="help-block"]').first().remove();
                    });

                    $.ajax({
                        url: '{{ action('Frontend\UserController@registerCollaborator') }}',
                        type: 'post',
                        data: '_token=' + $('input[name="_token"]').first().val() + '&' + formElem.serialize(),
                        success: function(result) {
                            if(result)
                            {
                                if(result == 'Success')
                                {
                                    $('#CollaboratorSignUpModal').modal('hide');

                                    swal({
                                        title: '@lang('theme.sign_up_success')',
                                        type: 'success',
                                        allowEscapeKey: false,
                                        showConfirmButton: false
                                    });

                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000);
                                }
                                else
                                {
                                    result = JSON.parse(result);

                                    for(var name in result)
                                    {
                                        if(result.hasOwnProperty(name))
                                        {
                                            formElem.find('input[name="' + name + '"]').first().parent().addClass('has-error').append('' +
                                                '<span class="help-block">' + result[name][0] + '</span>' +
                                            '');
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endpush
    @endif

@stop

@extends('frontend.layouts.main')

@section('page_heading', trans('theme.certificate'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.certificate')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="main_content">

                            @foreach($certificates as $certificate)
                                <div class="row item_news">                                    
                                    <div class="col-lg-7">
                                        <h4>{{ \App\Libraries\Helpers\Utility::getValueByLocale($certificate, 'name') }}</h4>
                                    </div>
                                    <div class="col-lg-3">
                                        @if(!empty($certificate->price))
                                            <p><b>@lang('theme.certificate_price'):</b> {{ \App\Libraries\Helpers\Utility::formatNumber($certificate->price) . 'đ' }}</p>
                                        @endif
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-lg btnThemGH CertificateSignUp" data-certificate-id="{{ $certificate->id }}">@lang('theme.sign_up')</button>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <ul class="pagination">
                                        @if($certificates->lastPage() > 1)
                                            @if($certificates->currentPage() > 1)
                                                <li><a href="{{ $certificates->previousPageUrl() }}">&laquo;</a></li>
                                                <li><a href="{{ $certificates->url(1) }}">1</a></li>
                                            @endif

                                            @for($i = 2;$i >= 1;$i --)
                                                @if($certificates->currentPage() - $i > 1)
                                                    @if($certificates->currentPage() - $i > 2 && $i == 2)
                                                        <li>...</li>
                                                        <li><a href="{{ $certificates->url($certificates->currentPage() - $i) }}">{{ $certificates->currentPage() - $i }}</a></li>
                                                    @else
                                                        <li><a href="{{ $certificates->url($certificates->currentPage() - $i) }}">{{ $certificates->currentPage() - $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            <li class="active"><a href="javascript:void(0)">{{ $certificates->currentPage() }}</a></li>

                                            @for($i = 1;$i <= 2;$i ++)
                                                @if($certificates->currentPage() + $i < $certificates->lastPage())
                                                    @if($certificates->currentPage() + $i < $certificates->lastPage() - 1 && $i == 2)
                                                        <li><a href="{{ $certificates->url($certificates->currentPage() + $i) }}">{{ $certificates->currentPage() + $i }}</a></li>
                                                        <li>...</li>
                                                    @else
                                                        <li><a href="{{ $certificates->url($certificates->currentPage() + $i) }}">{{ $certificates->currentPage() + $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            @if($certificates->currentPage() < $certificates->lastPage())
                                                <li><a href="{{ $certificates->url($certificates->lastPage()) }}">{{ $certificates->lastPage() }}</a></li>
                                                <li><a href="{{ $certificates->nextPageUrl() }}">&raquo;</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="CertificateSignUpModal" class="modal fade modal_general" data-backdrop="static" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">@lang('theme.sign_up') <span class="logo_small"><img src="{{ asset('themes/images/logo_small.png') }}" alt="Logo" class="img-responsive"></span></h4>
                </div>
                <div class="modal-body">
                    <form action="{{ action('Frontend\CertificateController@registerCertificate') }}" method="POST" role="form" class="frm_dangky" id="CertificateSignUpForm">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="* @lang('theme.name')" required="required" />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone" placeholder="* @lang('theme.phone')" required="required" />
                        </div>
                        <input type="hidden" name="certificate_id" />
                        <button type="submit" class="btn btn-block btnDangky">@lang('theme.sign_up')</button>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script type="text/javascript">
        $('.CertificateSignUp').click(function() {
            $('input[name="certificate_id"]').val($(this).data('certificate-id'));
            $('#CertificateSignUpModal').modal('show');
        });

        $('#CertificateSignUpForm').submit(function(e) {
            e.preventDefault();

            var formElem = $(this);

            formElem.find('input').each(function() {
                $(this).parent().removeClass('has-error').find('span[class="help-block"]').first().remove();
            });

            $.ajax({
                url: '{{ action('Frontend\CertificateController@registerCertificate') }}',
                type: 'post',
                data: '_token=' + $('input[name="_token"]').first().val() + '&' + formElem.serialize(),
                success: function(result) {
                    if(result)
                    {
                        if(result == 'Success')
                        {
                            $('#CertificateSignUpModal').modal('hide');

                            formElem.find('input').each(function() {
                                $(this).val('');
                            });

                            swal({
                                title: '@lang('theme.sign_up_success')',
                                type: 'success',
                                confirmButtonClass: 'btn-success'
                            });
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
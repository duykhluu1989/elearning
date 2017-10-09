@extends('frontend.layouts.main')

@section('page_heading', trans('theme.teacher_label'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.theme.teacher_label')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.teacher_label')</h5>

                            @include('frontend.teachers.partials.navigation')

                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>@lang('theme.order_number')</th>
                                        <th>@lang('theme.time')</th>
                                        <th>@lang('theme.course')</th>
                                        <th>@lang('theme.type')</th>
                                        <th>@lang('theme.percent')</th>
                                        <th>@lang('theme.amount')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <form action="{{ action('Frontend\TeacherController@adminTeacherTransaction') }}" id="TeacherTransactionFilterForm" method="get">
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="order" placeholder="@lang('theme.search_order')" value="{{ request()->input('order') }}" />
                                            </td>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control" name="course" placeholder="@lang('theme.search_course')" value="{{ request()->input('course') }}" />
                                            </td>
                                            <td>
                                                <?php
                                                $type = request()->input('type');
                                                ?>
                                                <select class="form-control" name="type" id="TeacherTransactionTypeFilter">
                                                    <option value=""></option>
                                                    @foreach(\App\Models\CollaboratorTransaction::getTransactionType(null, \App::getLocale()) as $value => $label)
                                                        @if($type !== null && $type == $value)
                                                            <option selected="selected" value="{{ $value }}">{{ $label }}</option>
                                                        @else
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td></td>
                                            <td>
                                                <button type="submit" class="hidden"></button>
                                            </td>
                                        </tr>
                                    </form>

                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                @if(!empty($transaction->order))
                                                    {{ $transaction->order->number }}
                                                @endif
                                            </td>
                                            <td>{{ $transaction->created_at }}</td>
                                            <td>
                                                @if(!empty($transaction->course))
                                                    {{ \App\Libraries\Helpers\Utility::getValueByLocale($transaction->course, 'name') }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ \App\Models\CollaboratorTransaction::getTransactionType($transaction->type, \App::getLocale()) }}
                                            </td>
                                            <td>
                                                @if(!empty($transaction->commission_percent))
                                                    {{ $transaction->commission_percent . '%' }}
                                                @endif
                                            </td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($transaction->commission_amount) . 'đ' }}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <ul class="pagination">
                                        @if($transactions->lastPage() > 1)
                                            @if($transactions->currentPage() > 1)
                                                <li><a href="{{ $transactions->previousPageUrl() }}">&laquo;</a></li>
                                                <li><a href="{{ $transactions->url(1) }}">1</a></li>
                                            @endif

                                            @for($i = 2;$i >= 1;$i --)
                                                @if($transactions->currentPage() - $i > 1)
                                                    @if($transactions->currentPage() - $i > 2 && $i == 2)
                                                        <li>...</li>
                                                        <li><a href="{{ $transactions->url($transactions->currentPage() - $i) }}">{{ $transactions->currentPage() - $i }}</a></li>
                                                    @else
                                                        <li><a href="{{ $transactions->url($transactions->currentPage() - $i) }}">{{ $transactions->currentPage() - $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            <li class="active"><a href="javascript:void(0)">{{ $transactions->currentPage() }}</a></li>

                                            @for($i = 1;$i <= 2;$i ++)
                                                @if($transactions->currentPage() + $i < $transactions->lastPage())
                                                    @if($transactions->currentPage() + $i < $transactions->lastPage() - 1 && $i == 2)
                                                        <li><a href="{{ $transactions->url($transactions->currentPage() + $i) }}">{{ $transactions->currentPage() + $i }}</a></li>
                                                        <li>...</li>
                                                    @else
                                                        <li><a href="{{ $transactions->url($transactions->currentPage() + $i) }}">{{ $transactions->currentPage() + $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            @if($transactions->currentPage() < $transactions->lastPage())
                                                <li><a href="{{ $transactions->url($transactions->lastPage()) }}">{{ $transactions->lastPage() }}</a></li>
                                                <li><a href="{{ $transactions->nextPageUrl() }}">&raquo;</a></li>
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

@stop

@push('scripts')
    <script type="text/javascript">
        $('#TeacherTransactionTypeFilter').change(function() {
            $('#TeacherTransactionFilterForm').submit();
        });
    </script>
@endpush
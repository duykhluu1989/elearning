@extends('frontend.layouts.main')

@section('page_heading', trans('theme.collaborator'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.layouts.partials.breabcrumb', ['breabcrumbTitle' => trans('theme.collaborator')])

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="navleft">
                            <h5>@lang('theme.collaborator')</h5>

                            @include('frontend.collaborators.partials.navigation')

                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>@lang('theme.code')</th>
                                        <th>@lang('theme.name')</th>
                                        <th>@lang('theme.rank')</th>
                                        <th>@lang('theme.total_commission')</th>
                                        <th>@lang('theme.total_revenue')</th>
                                        <th>@lang('theme.commission')</th>
                                        <th>@lang('theme.create_discount')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <form action="{{ action('Frontend\CollaboratorController@adminCollaboratorDownLine') }}" id="CollaboratorFilterForm" method="get">
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="code" placeholder="@lang('theme.search_code')" value="{{ request()->input('code') }}" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="name" placeholder="@lang('theme.search_name')" value="{{ request()->input('name') }}" />
                                            </td>
                                            <td>
                                                <?php
                                                $rank = request()->input('rank');
                                                ?>
                                                <select class="form-control" name="rank" id="CollaboratorRankFilter">
                                                    <option value=""></option>
                                                    @foreach(\App\Models\Collaborator::getCollaboratorRank() as $value => $label)
                                                        @if($rank == $value)
                                                            <option selected="selected" value="{{ $value }}">{{ $label }}</option>
                                                        @else
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <button type="submit" class="hidden"></button>
                                            </td>
                                        </tr>
                                    </form>

                                    @foreach($collaborators as $collaborator)
                                        <tr>
                                            <td>
                                                <a class="label label-danger" href="{{ action('Frontend\CollaboratorController@editCollaboratorDownLine', ['id' => $collaborator->id]) }}">{{ $collaborator->collaboratorInformation->code }}</a>
                                            </td>
                                            <td>{{ $collaborator->profile->name }}</td>
                                            <td>{{ $collaborator->collaboratorInformation->rank->name }}</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($collaborator->collaboratorInformation->total_commission) . 'đ' }}</td>
                                            <td>{{ \App\Libraries\Helpers\Utility::formatNumber($collaborator->collaboratorInformation->total_revenue) . 'đ' }}</td>
                                            <td>{{ $collaborator->collaboratorInformation->commission_percent . '%' }}</td>
                                            <td>{{ $collaborator->collaboratorInformation->create_discount_percent . '%' }}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <ul class="pagination">
                                        @if($collaborators->lastPage() > 1)
                                            @if($collaborators->currentPage() > 1)
                                                <li><a href="{{ $collaborators->previousPageUrl() }}">&laquo;</a></li>
                                                <li><a href="{{ $collaborators->url(1) }}">1</a></li>
                                            @endif

                                            @for($i = 2;$i >= 1;$i --)
                                                @if($collaborators->currentPage() - $i > 1)
                                                    @if($collaborators->currentPage() - $i > 2 && $i == 2)
                                                        <li>...</li>
                                                        <li><a href="{{ $collaborators->url($collaborators->currentPage() - $i) }}">{{ $collaborators->currentPage() - $i }}</a></li>
                                                    @else
                                                        <li><a href="{{ $collaborators->url($collaborators->currentPage() - $i) }}">{{ $collaborators->currentPage() - $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            <li class="active"><a href="javascript:void(0)">{{ $collaborators->currentPage() }}</a></li>

                                            @for($i = 1;$i <= 2;$i ++)
                                                @if($collaborators->currentPage() + $i < $collaborators->lastPage())
                                                    @if($collaborators->currentPage() + $i < $collaborators->lastPage() - 1 && $i == 2)
                                                        <li><a href="{{ $collaborators->url($collaborators->currentPage() + $i) }}">{{ $collaborators->currentPage() + $i }}</a></li>
                                                        <li>...</li>
                                                    @else
                                                        <li><a href="{{ $collaborators->url($collaborators->currentPage() + $i) }}">{{ $collaborators->currentPage() + $i }}</a></li>
                                                    @endif
                                                @endif
                                            @endfor

                                            @if($collaborators->currentPage() < $collaborators->lastPage())
                                                <li><a href="{{ $collaborators->url($collaborators->lastPage()) }}">{{ $collaborators->lastPage() }}</a></li>
                                                <li><a href="{{ $collaborators->nextPageUrl() }}">&raquo;</a></li>
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
        $('#CollaboratorRankFilter').change(function() {
            $('#CollaboratorFilterForm').submit();
        });
    </script>
@endpush
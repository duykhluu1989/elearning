@extends('frontend.layouts.main')

@section('page_heading', trans('theme.collaborator'))

@section('section')

    @include('frontend.layouts.partials.header')

    @include('frontend.layouts.partials.headtext')

    @include('frontend.collaborators.partials.collaborator_down_line_breadcrumb')

    <main>
        <section class="khoahoc bg_gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="main_content">
                            <div class="row">
                                <h4>@lang('theme.collaborator') - {{ $collaborator->collaboratorInformation->code }}</h4>
                                <form action="{{ action('Frontend\CollaboratorController@editCollaboratorDownLine', ['id' => $collaborator->id]) }}" method="POST">
                                    <?php
                                    $collaboratorRank = json_decode($collaborator->collaboratorInformation->rank->value, true);
                                    ?>
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-4">@lang('theme.commission') <i>(@lang('theme.max'): {{ $collaboratorRank[\App\Models\Collaborator::COMMISSION_ATTRIBUTE] . '%' }})</i></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="commission_percent" value="{{ old('commission_percent', $collaborator->collaboratorInformation->commission_percent) }}" required="required" />
                                                <span class="input-group-addon">%</span>
                                            </div>
                                        </div>
                                        @if($errors->has('commission_percent'))
                                            <div class="form-group has-error">
                                                <span class="help-block">* {{ $errors->first('commission_percent') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-4">@lang('theme.create_discount') <i>(@lang('theme.max'): {{ $collaboratorRank[\App\Models\Collaborator::DISCOUNT_ATTRIBUTE] . '%' }})</i></label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="create_discount_percent" value="{{ old('create_discount_percent', $collaborator->collaboratorInformation->create_discount_percent) }}" required="required" />
                                                <span class="input-group-addon">%</span>
                                            </div>
                                        </div>
                                        @if($errors->has('create_discount_percent'))
                                            <div class="form-group has-error">
                                                <span class="help-block">* {{ $errors->first('create_discount_percent') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-lg btnRed"><i class="fa fa-floppy-o" aria-hidden="true"></i> @lang('theme.save')</button>
                                    </div>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@stop
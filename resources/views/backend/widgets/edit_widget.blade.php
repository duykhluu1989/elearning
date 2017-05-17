@extends('backend.layouts.main')

@section('page_heading', 'Chỉnh Sửa Tiện Ích - ' . $widget->name)

@section('section')

    <form action="{{ action('Backend\WidgetController@editWidget', ['id' => $widget->id]) }}" method="post" enctype="multipart/form-data">

        <div class="box box-primary">
            <div class="box-header with-border">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\WidgetController@adminWidget') }}" class="btn btn-default">Quay lai</a>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Trạng Thái</label>
                            <select class="form-control" name="status">
                                <?php
                                $status = old('status', $widget->status);
                                ?>
                                @foreach(\App\Models\Widget::getWidgetStatus() as $value => $label)
                                    <?php
                                    if($value == \App\Models\Widget::STATUS_ACTIVE_DB)
                                        $optionClass = 'text-green';
                                    else
                                        $optionClass = 'text-red';
                                    ?>
                                    @if($status == $value)
                                        <option class="{{ $optionClass }}" value="{{ $value }}" selected="selected">{{ $label }}</option>
                                    @else
                                        <option class="{{ $optionClass }}" value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($widget->type == \App\Models\Widget::TYPE_SLIDER_DB)

                        @include('backend.widgets.partials.widget_slider_form')

                    @endif

                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ action('Backend\WidgetController@adminWidget') }}" class="btn btn-default">Quay lai</a>
            </div>
        </div>
        {{ csrf_field() }}

    </form>

@stop

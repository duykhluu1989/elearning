<?php
$attributes = json_decode($widget->attribute, true);

$details = array();
if(!empty($widget->detail))
    $details = json_decode($widget->detail, true);
?>
<div class="col-sm-12">
    <div class="form-group">
        <label>Chi Tiết</label>
        <div class="no-padding">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                <tr>
                    @foreach($attributes as $attribute)
                        <th>{{ $attribute['title'] }}</th>
                    @endforeach
                    <th>
                        <button type="button" id="NewSliderItemButton" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                    </th>
                </tr>
                </thead>
                <tbody id="ListSliderItem">
                @foreach($details as $detail)
                    <tr>
                        @foreach($attributes as $attribute)
                            <td>{{ $detail[$attribute['name']] }}</td>
                        @endforeach
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="NewSliderItemPrototype" class="hidden">
    <tr>
        @foreach($attributes as $attribute)
            <th>
                @if($attribute['type'] == \App\Models\Widget::ATTRIBUTE_TYPE_IMAGE_DB)
                @else
                    <input type="text" class="form-control" name />
                @endif
            </th>
        @endforeach
    </tr>
</div>

@push('scripts')
    <script type="text/javascript">
        $('#NewSliderItemButton').click(function() {
            $('#ListSliderItem').append();
        });
    </script>
@endpush
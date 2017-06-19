<?php
$attributes = json_decode($widget->attribute, true);

$details = array();
if(!empty($widget->detail))
    $details = json_decode($widget->detail, true);
?>
<div class="col-sm-12">
    <div class="form-group">
        <label>Tiêu Đề Nhóm <i>(bắt buộc)</i></label>
        <input type="text" class="form-control" name="custom_detail[title]" value="{{ isset($details['custom_detail']['title']) ? $details['custom_detail']['title'] : '' }}" required="required" />
    </div>
</div>

<div class="col-sm-12">
    <div class="form-group">
        <label>Tiêu Đề Nhóm EN</label>
        <input type="text" class="form-control" name="custom_detail[title_en]" value="{{ isset($details['custom_detail']['title_en']) ? $details['custom_detail']['title_en'] : '' }}" />
    </div>
</div>

<?php
unset($details['custom_detail']);
?>

<div class="col-sm-12">
    <div class="form-group">
        <label>Chi Tiết <i>(kéo thả để thay đổi thứ tự)</i></label>
        <div class="no-padding">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                <tr>
                    @foreach($attributes as $attribute)
                        <th>{{ $attribute['title'] }}</th>
                    @endforeach
                    <th class="col-sm-1 text-center">
                        <button type="button" class="btn btn-primary" id="NewCourseItemButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                    </th>
                </tr>
                </thead>
                <tbody id="ListCourseItem">
                @foreach($details as $detail)
                    <tr>
                        @foreach($attributes as $attribute)
                            <td>
                                <input type="text" class="form-control CourseInput" value="{{ isset($detail[$attribute['name']]) ? \App\Models\Course::select('name')->find($detail[$attribute['name']])->name : '' }}" />
                                <input type="hidden" name="detail[{{ $attribute['name'] }}][]" value="{{ isset($detail[$attribute['name']]) ? $detail[$attribute['name']] : '' }}" />
                            </td>
                        @endforeach
                        <td class="text-center">
                            <button type="button" class="btn btn-default RemoveCourseItemButton"><i class="fa fa-trash-o fa-fw"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $('#NewCourseItemButton').click(function() {
            $('#ListCourseItem').append('' +
                '<tr>' +
                @foreach($attributes as $attribute)
                    '<td>' +
                    '<input type="text" class="form-control CourseInput" />' +
                    '<input type="hidden" name="detail[{{ $attribute['name'] }}][]" />' +
                    '</td>' +
                @endforeach
                '<td class="text-center">' +
                '<button type="button" class="btn btn-default RemoveCourseItemButton"><i class="fa fa-trash-o fa-fw"></i></button>' +
                '</td>' +
            '</tr>');
        });

        $('#ListCourseItem').on('focusin', 'input', function() {
            if($(this).hasClass('CourseInput'))
            {
                $(this).autocomplete({
                    minLength: 3,
                    delay: 1000,
                    source: function(request, response) {
                        $.ajax({
                            url: '{{ action('Backend\CourseController@autoCompleteCourse') }}',
                            type: 'post',
                            data: '_token=' + $('input[name="_token"]').first().val() + '&term=' + request.term,
                            success: function(result) {
                                if(result)
                                {
                                    result = JSON.parse(result);
                                    response(result);
                                }
                            }
                        });
                    },
                    select: function(event, ui) {
                        $(this).val(ui.item.name);
                        $(this).parent().find('input[type="hidden"]').first().val(ui.item.id);
                        return false;
                    }
                }).autocomplete('instance')._renderItem = function(ul, item) {
                    return $('<li>').append('<a>' + item.name + '</a>').appendTo(ul);
                };
            }
        }).on('click', 'button', function() {
            if($(this).hasClass('RemoveCourseItemButton'))
                $(this).parent().parent().remove();
        }).sortable({
            revert: true,
            cursor: 'move'
        });
    </script>
@endpush
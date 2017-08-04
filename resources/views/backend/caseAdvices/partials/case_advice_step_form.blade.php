<div class="box box-primary">
    <div class="box-header with-border">
        <button type="submit" class="btn btn-primary">{{ empty($caseStep->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CaseAdviceController@adminCaseAdviceStep', ['id' => $caseStep->case_id]) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($caseStep->id))
            <a href="{{ action('Backend\CaseAdviceController@deleteCaseAdviceStep', ['id' => $caseStep->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="page-header">Bước Giải Quyết {{ $caseStep->step }}</h2>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Loại</label>
                    <?php
                    $type = old('type', $caseStep->type);
                    ?>
                    <div>
                        @foreach(\App\Models\CaseAdviceStep::getCaseAdviceStepType() as $value => $label)
                            @if($type == $value)
                                <label class="radio-inline">
                                    <input type="radio" name="type" checked="checked" value="{{ $value }}">{{ $label }}
                                </label>
                            @else
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="{{ $value }}">{{ $label }}
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group{{ $errors->has('content') ? ' has-error': '' }}">
                    <label id="CaseAdviceStepContentLabel">Nội Dung<?php echo ($type == \App\Models\CaseAdviceStep::TYPE_FREE_DB ? ' <i>(bắt buộc)</i>' : ''); ?></label>
                    @if($errors->has('content'))
                        <span class="help-block">{{ $errors->first('content') }}</span>
                    @endif
                    <textarea class="form-control" rows="10" name="content" id="CaseAdviceStepContentInput"<?php echo ($type == \App\Models\CaseAdviceStep::TYPE_FREE_DB ? ' required="required"' : ''); ?>>{{ old('content', $caseStep->content) }}</textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Nội Dung EN</label>
                    <textarea class="form-control" rows="10" name="content_en">{{ old('content_en', $caseStep->content_en) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-primary">{{ empty($caseStep->id) ? 'Tạo Mới' : 'Cập Nhật' }}</button>
        <a href="{{ action('Backend\CaseAdviceController@adminCaseAdviceStep', ['id' => $caseStep->case_id]) }}" class="btn btn-default">Quay Lại</a>

        @if(!empty($caseStep->id))
            <a href="{{ action('Backend\CaseAdviceController@deleteCaseAdviceStep', ['id' => $caseStep->id]) }}" class="btn btn-primary pull-right Confirmation">Xóa</a>
        @endif
    </div>
</div>
{{ csrf_field() }}

@push('scripts')
    <script type="text/javascript">
        $('input[type="radio"][name="type"]').change(function() {
            if($(this).val() == '{{ \App\Models\CaseAdviceStep::TYPE_FREE_DB }}')
            {
                $('#CaseAdviceStepContentInput').prop('required', 'required');
                $('#CaseAdviceStepContentLabel').html('Nội Dung <i>(bắt buộc)</i>');
            }
            else
            {
                $('#CaseAdviceStepContentInput').removeAttr('required');
                $('#CaseAdviceStepContentLabel').html('Nội Dung');
            }
        });
    </script>
@endpush
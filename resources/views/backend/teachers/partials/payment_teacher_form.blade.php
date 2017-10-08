<div class="row">
    <div class="col-sm-12">
        <div class="form-group{{ $errors->has('amount') ? ' has-error': '' }}">
            <label>Số Tiền <i>(bắt buộc)</i></label>
            <div class="input-group">
                <input type="text" class="form-control InputForNumber" name="amount" value="{{ request()->input('amount',  \App\Libraries\Helpers\Utility::formatNumber($teacher->teacherInformation->current_commission)) }}" required="required" />
                <span class="input-group-addon">VND</span>
            </div>
            @if($errors->has('amount'))
                <span class="help-block">{{ $errors->first('amount') }}</span>
            @endif
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>Ghi Chú</label>
            <input type="text" class="form-control" name="note" maxlength="255" value="{{ request()->input('note') }}" />
        </div>
    </div>
</div>
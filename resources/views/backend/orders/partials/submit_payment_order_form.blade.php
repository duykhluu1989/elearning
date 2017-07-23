<div class="row">
    <div class="col-sm-12">
        <div class="form-group{{ $errors->has('amount') ? ' has-error': '' }}">
            <label>Số Tiền <i>(bắt buộc)</i></label>
            <input type="text" class="form-control InputForNumber" name="amount" value="{{ request()->input('amount', \App\Libraries\Helpers\Utility::formatNumber($order->total_price)) }}" required="required" />
            @if($errors->has('amount'))
                <span class="help-block">{{ $errors->first('amount') }}</span>
            @endif
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>Ghi chú</label>
            <input type="text" class="form-control" name="note" value="{{ request()->input('note') }}" />
        </div>
    </div>
</div>
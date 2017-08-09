<?php
$details = array();

if(!empty($paymentMethod->detail))
    $details = json_decode($paymentMethod->detail, true);
?>
<div class="row">
    <div class="col-sm-12">
        <h2 class="page-header">Thông Tin Kết Nối Thật</h2>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Merchant ID</label>
            <input type="text" class="form-control" name="detail[merchant_id_live]" value="{{ isset($details['merchant_id_live']) ? $details['merchant_id_live'] : '' }}" />
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Access Code</label>
            <input type="text" class="form-control" name="detail[access_code_live]" value="{{ isset($details['access_code_live']) ? $details['access_code_live'] : '' }}" />
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Hash Code</label>
            <input type="text" class="form-control" name="detail[hash_code_live]" value="{{ isset($details['hash_code_live']) ? $details['hash_code_live'] : '' }}" />
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>Payment Url</label>
            <input type="text" class="form-control" name="detail[payment_url_live]" value="{{ isset($details['payment_url_live']) ? $details['payment_url_live'] : '' }}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h2 class="page-header">Thông Tin Kết Nối Thử Nghiệm</h2>
    </div>
</div>
<div class="row">
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Kết Nối Thật</label>
            <?php
            $live = ((isset($details['live']) && $details['live'] == \App\Libraries\Helpers\Utility::ACTIVE_DB) ? \App\Libraries\Helpers\Utility::ACTIVE_DB : \App\Libraries\Helpers\Utility::INACTIVE_DB);
            ?>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="detail[live]" value="{{ \App\Libraries\Helpers\Utility::ACTIVE_DB }}"<?php echo ($live == \App\Libraries\Helpers\Utility::ACTIVE_DB ? ' checked="checked"' : ''); ?> data-toggle="toggle" data-on="{{ \App\Libraries\Helpers\Utility::TRUE_LABEL }}" data-off="{{ \App\Libraries\Helpers\Utility::FALSE_LABEL }}" data-onstyle="success" data-offstyle="danger" />
                </label>
            </div>
        </div>
    </div>
</div>
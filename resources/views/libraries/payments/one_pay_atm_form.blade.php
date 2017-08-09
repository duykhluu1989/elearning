<?php
$details = array();

if(!empty($paymentMethod->detail))
    $details = json_decode($paymentMethod->detail, true);
?>

<div class="box-header with-border">
    <h3 class="box-title">Thông Tin Kết Nội Thật</h3>
</div>
<div class="box-body">
    <div class="row">
    </div>
</div>
<div class="box-header with-border">
    <h3 class="box-title">Thông Tin Kết Nối Thử Nghiệm</h3>
</div>
<div class="box-body">
    <div class="row">
    </div>
</div>
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
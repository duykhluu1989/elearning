<?php
$details = array();

if(!empty($paymentMethod->detail))
    $details = json_decode($paymentMethod->detail, true);
?>
<div class="col-sm-12">
    <div class="form-group">
        <label>Tài khoản nhận chuyển khoản <i>(kéo thả để thay đổi thứ tự)</i></label>
        <div class="no-padding">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>Số tài khoản</th>
                    <th>Chủ tài khoản</th>
                    <th>Ngân hàng</th>
                    <th class="col-sm-1 text-center">
                        <button type="button" class="btn btn-primary" id="NewBankAccountItemButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                    </th>
                </tr>
                </thead>
                <tbody id="ListBankAccountItem">
                @foreach($details as $detail)
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="detail[bank_number][]" value="{{ isset($detail['bank_number']) ? $detail['bank_number'] : '' }}" required="required" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="detail[bank_name][]" value="{{ isset($detail['bank_name']) ? $detail['bank_name'] : '' }}" required="required" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="detail[bank][]" value="{{ isset($detail['bank']) ? $detail['bank'] : '' }}" required="required" />
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-default RemoveBannerItemButton"><i class="fa fa-trash-o fa-fw"></i></button>
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
        $('#NewBankAccountItemButton').click(function() {
            $('#ListBankAccountItem').append('' +
                '<tr>' +
                '<td>' +
                '<input type="text" class="form-control" name="detail[bank_number][]" required="required" />' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="detail[bank_name][]" required="required" />' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="detail[bank][]" required="required" />' +
                '</td>' +
                '<td class="text-center">' +
                '<button type="button" class="btn btn-default RemoveBankAccountItemButton"><i class="fa fa-trash-o fa-fw"></i></button>' +
                '</td>' +
            '</tr>');
        });

        $('#ListBankAccountItem').on('click', 'button', function() {
            if($(this).hasClass('RemoveBankAccountItemButton'))
                $(this).parent().parent().remove();
        }).sortable({
            revert: true,
            cursor: 'move'
        });
    </script>
@endpush

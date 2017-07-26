<?php
$details = array();

if(!empty($paymentMethod->detail))
    $details = json_decode($paymentMethod->detail, true);
?>
<div class="col-sm-12">
    <div class="form-group">
        <label>Địa chỉ văn phòng <i>(kéo thả để thay đổi thứ tự)</i></label>
        <div class="no-padding">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>Địa chỉ</th>
                    <th class="col-sm-1 text-center">
                        <button type="button" class="btn btn-primary" id="NewAddressItemButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Thêm Mới"><i class="fa fa-plus fa-fw"></i></button>
                    </th>
                </tr>
                </thead>
                <tbody id="ListAddressItem">
                @foreach($details as $detail)
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="detail[address][]" value="{{ isset($detail['address']) ? $detail['address'] : '' }}" required="required" />
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-default RemoveAddressItemButton"><i class="fa fa-trash-o fa-fw"></i></button>
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
        $('#NewAddressItemButton').click(function() {
            $('#ListAddressItem').append('' +
                '<tr>' +
                '<td>' +
                '<input type="text" class="form-control" name="detail[address][]" required="required" />' +
                '</td>' +
                '<td class="text-center">' +
                '<button type="button" class="btn btn-default RemoveBankAccountItemButton"><i class="fa fa-trash-o fa-fw"></i></button>' +
                '</td>' +
            '</tr>');
        });

        $('#ListAddressItem').on('click', 'button', function() {
            if($(this).hasClass('RemoveAddressItemButton'))
                $(this).parent().parent().remove();
        }).sortable({
            revert: true,
            cursor: 'move'
        });
    </script>
@endpush

@extends('backend.layouts.main')

@section('page_heading', 'Lịch Sử Hoa Hồng Cộng Tác Viên - ' . $collaborator->username)

@section('section')

    <?php

    $gridView->setTools([
        function() {
            echo \App\Libraries\Helpers\Html::a('Quay Lại', [
                'href' => \App\Libraries\Helpers\Utility::getBackUrlCookie(action('Backend\UserController@adminUserCollaborator')),
                'class' => 'btn btn-default',
            ]);
        },
        function() {
            echo \App\Libraries\Helpers\Html::button('Xác Nhận Thanh Toán', [
                'class' => 'btn btn-primary SubmitPaymentButton',
            ]);
        },
    ]);

    $gridView->render();

    ?>


    <div class="modal fade" tabindex="-1" role="dialog" id="SubmitPaymentModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content box" id="SubmitPaymentModalContent">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Xác Nhận Thanh Toán</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ action('Backend\CollaboratorController@paymentCollaborator', ['id' => $collaborator->id]) }}" method="post" id="SubmitPaymentModalForm">

                        @include('backend.collaborators.partials.payment_collaborator_form')

                    </form>
                    {{ csrf_field() }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="SubmitPaymentModalSubmitButton">Xác Nhận</button>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script type="text/javascript">
        $('#SubmitPaymentModalForm').on('keyup', 'input', function() {
            if($(this).hasClass('InputForNumber'))
                $(this).val(formatNumber($(this).val(), '.'));
        });

        $('.SubmitPaymentButton').click(function() {
            $('#SubmitPaymentModal').modal('show');
        });

        $('#SubmitPaymentModalSubmitButton').click(function() {
            var submitPaymentModalFormElem = $('#SubmitPaymentModalForm');

            if(submitPaymentModalFormElem[0].checkValidity() == false)
                submitPaymentModalFormElem[0].reportValidity();
            else
            {
                $('#SubmitPaymentModalContent').append('' +
                    '<div class="overlay">' +
                    '<i class="fa fa-refresh fa-spin"></i>' +
                    '</div>' +
                '');

                $.ajax({
                    url: submitPaymentModalFormElem.prop('action'),
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&' + submitPaymentModalFormElem.serialize(),
                    success: function(result) {
                        if(result)
                        {
                            if(result == 'Success')
                                location.reload();
                            else
                            {
                                submitPaymentModalFormElem.html(result);

                                $('#SubmitPaymentModalContent').find('div[class="overlay"]').first().remove();
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
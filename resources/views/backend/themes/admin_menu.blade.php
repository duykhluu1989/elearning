@extends('backend.layouts.main')

@section('page_heading', 'Menu')

@section('section')

    <div class="box box-primary">
        <div class="box-header with-border">
            <button class="btn btn-primary" id="NewMenuButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Menu Mới"><i class="fa fa-plus fa-fw"></i></button>
        </div>
        <div class="box-body no-padding">

        </div>
    </div>
    {{ csrf_field() }}

    <div class="modal fade" tabindex="-1" role="dialog" id="MenuModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content box" id="MenuModalContent">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="MenuModalTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="MenuModalForm">

                        @include('backend.themes.partials.menu_form')

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="MenuModalSubmitButton">Lưu</button>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script type="text/javascript">
        $('#MenuModalForm').on('change', 'input[type="radio"][name="type"]', function() {
            var nameFormGroupElem = $('#NameFormGroup');

            if($(this).val() == '{{ \App\Models\Menu::TYPE_STATIC_LINK_DB }}')
            {
                nameFormGroupElem.find('label').first().html('Tên <i>(bắt buộc)</i>');
                nameFormGroupElem.find('input').first().prop('required', 'required');
                $('#UrlFormGroup').show().find('input').first().prop('required', 'required');
                $('#TargetNameFormGroup').hide().find('input').first().removeAttr('required').val('');
            }
            else
            {
                nameFormGroupElem.find('label').first().html('Tên');
                nameFormGroupElem.find('input').first().removeAttr('required');
                $('#UrlFormGroup').hide().find('input').first().removeAttr('required').val('');

                var targetNameFormGroupElem = $('#TargetNameFormGroup');
                targetNameFormGroupElem.show();

                if($(this).val() == '{{ \App\Models\Menu::TYPE_CATEGORY_DB }}')
                {
                    targetNameFormGroupElem.find('label').first().html('{{ \App\Models\Menu::TYPE_CATEGORY_LABEL }} <i>(bắt buộc)</i>');
                    targetNameFormGroupElem.find('input').first().prop('required', 'required');
                }
                else if($(this).val() == '{{ \App\Models\Menu::TYPE_STATIC_ARTICLE_DB }}')
                {
                    targetNameFormGroupElem.find('label').first().html('{{ \App\Models\Menu::TYPE_STATIC_ARTICLE_LABEL }} <i>(bắt buộc)</i>');
                    targetNameFormGroupElem.find('input').first().prop('required', 'required');
                }
                else
                {
                    targetNameFormGroupElem.find('label').first().html('{{ \App\Models\Menu::TYPE_CATEGORY_LABEL }}');
                    targetNameFormGroupElem.find('input').first().removeAttr('required');
                }
            }
        });

        $('#NewMenuButton').click(function() {
            $('#MenuModalTitle').html('Menu Mới');
            $('#MenuModal').modal('show');
        });

        $('#MenuModalSubmitButton').click(function() {
            var newMenuModalFormElem = $('#MenuModalForm');

            if(newMenuModalFormElem[0].checkValidity() == false)
                newMenuModalFormElem[0].reportValidity();
            else
            {
                $('#MenuModalContent').first().append('' +
                    '<div class="overlay">' +
                    '<i class="fa fa-refresh fa-spin"></i>' +
                    '</div>' +
                '');

                $.ajax({
                    url: '{{ action('Backend\ThemeController@createMenu') }}',
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&' + newMenuModalFormElem.serialize(),
                    success: function(result) {
                        if(result)
                        {
                            newMenuModalFormElem.html(result);

                            $('#MenuModalContent').find('div[class="overlay"]').first().remove();

                            if(newMenuModalFormElem.find('span[class="help-block"]').length < 1)
                            {
                                $('#MenuModal').modal('hide');
                            }
                        }
                    }
                });
            }
        });

        setInterval(function() {
            $.ajax({
                url: '{{ action('Backend\HomeController@refreshCsrfToken') }}',
                type: 'post',
                data: '_token=' + $('input[name="_token"]').first().val(),
                success: function(result) {
                    if(result)
                    {
                        $('input[name="_token"]').each(function() {
                            $(this).val(result);
                        });
                    }
                }
            });
        }, 60000);
    </script>
@endpush
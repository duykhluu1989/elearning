@extends('backend.layouts.main')

@section('page_heading', 'Menu')

@section('section')

    <div class="box box-primary" id="AdminMenuDiv">
        <div class="box-header with-border">
            <button type="submit" class="btn btn-primary">Cập Nhật</button>

            <button class="btn btn-primary pull-right NewMenuButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Menu Mới"><i class="fa fa-plus fa-fw"></i></button>
        </div>
        <div class="box-body">

            @include('backend.themes.partials.list_menu', ['listMenus' => $rootMenus, 'listId' => 'ListMenuItem'])

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Cập Nhật</button>

            <button class="btn btn-primary pull-right NewMenuButton" data-container="body" data-toggle="popover" data-placement="top" data-content="Menu Mới"><i class="fa fa-plus fa-fw"></i></button>
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
                    <button type="button" class="btn btn-primary" id="MenuModalSubmitButton"></button>
                </div>
            </div>
        </div>
    </div>

@stop

@push('stylesheets')
    <style type="text/css">
        #ListMenuItem, #ListMenuItem ul {
            list-style-type: none;
        }

        .MenuItemPlaceholder {
            outline: 1px dashed #4183C4;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/jquery.mjs.nestedSortable.js') }}"></script>
    <script type="text/javascript">
        $('#ListMenuItem').nestedSortable({
            forcePlaceholderSize: true,
            handle: 'div',
            items: 'li',
            placeholder: 'MenuItemPlaceholder',
            tolerance: 'pointer',
            toleranceElement: '> div',
            isTree: true,
            expandOnHover: 700,
            listType: 'ul'
        });

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

        $('.NewMenuButton').click(function() {
            $('#MenuModalTitle').html('Menu Mới');
            $('#MenuModalSubmitButton').html('Tạo Mới').val('create');
            $('#MenuModalForm').prop('action', '{{ action('Backend\ThemeController@createMenu') }}');

            clearForm();

            $('#MenuModal').modal('show');
        });

        function clearForm()
        {
            $('#MenuModalForm').find('input[type="text"]').each(function() {
                $(this).val('');
            });
        }

        $('#MenuModalSubmitButton').click(function() {
            var menuModalFormElem = $('#MenuModalForm');

            if(menuModalFormElem[0].checkValidity() == false)
                menuModalFormElem[0].reportValidity();
            else
            {
                $('#MenuModalContent').first().append('' +
                    '<div class="overlay">' +
                    '<i class="fa fa-refresh fa-spin"></i>' +
                    '</div>' +
                '');

                $.ajax({
                    url: menuModalFormElem.attr('action'),
                    type: 'post',
                    data: '_token=' + $('input[name="_token"]').first().val() + '&' + menuModalFormElem.serialize(),
                    success: function(result) {
                        if(result)
                        {
                            menuModalFormElem.html(result);

                            $('#MenuModalContent').find('div[class="overlay"]').first().remove();

                            if(menuModalFormElem.find('span[class="help-block"]').length < 1)
                            {
                                var menuModalSubmitButton = $('#MenuModalSubmitButton');
                                var menuId = '';
                                if(menuModalSubmitButton.val() != 'create')
                                {
                                    var menuModalSubmitButtonValArr = menuModalSubmitButton.val().split('_');
                                    menuId = menuModalSubmitButtonValArr[1];
                                }

                                $.ajax({
                                    url: '{{ action('Backend\ThemeController@getMenuHtml') }}',
                                    type: 'post',
                                    data: '_token=' + $('input[name="_token"]').first().val() + '&id=' + menuId,
                                    success: function(result) {
                                        if(result)
                                        {
                                            if(menuModalSubmitButton.val() == 'create')
                                                $('#ListMenuItem').append(result);
                                            else
                                                $('#EditMenuButton_' + menuId).parent().parent().html(result);
                                        }
                                    }
                                });

                                $('#MenuModal').modal('hide');

                                clearForm();
                            }
                        }
                    }
                });
            }
        });

        $('.EditMenuButton').click(function() {
            if($(this).val() != '')
            {
                var elemIdArr = $(this).attr('id').split('_');

                $('#AdminMenuDiv').first().append('' +
                    '<div class="overlay">' +
                    '<i class="fa fa-refresh fa-spin"></i>' +
                    '</div>' +
                '');

                $('#MenuModalTitle').html('Chỉnh Sửa Menu');
                $('#MenuModalSubmitButton').html('Cập Nhật').val('edit_' + elemIdArr[1]);
                $('#MenuModalForm').prop('action', $(this).val());

                $.ajax({
                    url: $(this).val(),
                    type: 'get',
                    success: function(result) {
                        if(result)
                        {
                            $('#MenuModalForm').html(result);

                            $('#AdminMenuDiv').find('div[class="overlay"]').first().remove();

                            $('#MenuModal').modal('show');
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
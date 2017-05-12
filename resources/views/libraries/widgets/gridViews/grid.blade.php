<div class="box box-primary">
    <div class="box-header with-border">
        @if(!empty($tools))
            @foreach($tools as $tool)
                @if(is_callable($tool))
                    <?php
                    call_user_func($tool);
                    ?>
                @endif
            @endforeach
        @endif
        <div class="box-tools">

            @include('libraries.widgets.gridViews.pagination')

        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped table-hover table-condensed">
            <thead>
            <tr>
                @if($checkbox == true)
                    <th><input type="checkbox" class="GridViewCheckBoxAll" /></th>
                @endif
                @foreach($columns as $column)
                    <th>{{ $column['title'] }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($dataProvider as $row)
                <tr>
                    @if($checkbox == true)
                        <td><input type="checkbox" class="GridViewCheckBox" value="{{ $row->id }}" /></td>
                    @endif
                    @foreach($columns as $column)
                        <td>
                            @if(is_callable($column['data']))
                                <?php
                                call_user_func($column['data'], $row);
                                ?>
                            @else
                                {{ $row->$column['data'] }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="box-footer">

        @include('libraries.widgets.gridViews.pagination')

    </div>
</div>

@if($checkbox == true)
    @push('scripts')
        <script type="text/javascript">
            $('.GridViewCheckBoxAll').click(function() {
                if($(this).prop('checked'))
                    $('.GridViewCheckBoxControl').show();
                else
                    $('.GridViewCheckBoxControl').hide();

                $('.GridViewCheckBox').prop('checked', $(this).prop('checked'));
            });

            $('.GridViewCheckBox').click(function() {
                if($(this).prop('checked'))
                {
                    $('.GridViewCheckBoxControl').show();

                    var allChecked = true;

                    $('.GridViewCheckBox').each(function() {
                        if(!$(this).prop('checked'))
                        {
                            allChecked = false;
                            return false;
                        }
                    });

                    if(allChecked)
                        $('.GridViewCheckBoxAll').first().prop('checked', $(this).prop('checked'));
                }
                else
                {
                    var noneChecked = true;

                    $('.GridViewCheckBox').each(function() {
                        if($(this).prop('checked'))
                        {
                            noneChecked = false;
                            return false;
                        }
                    });

                    if(noneChecked)
                        $('.GridViewCheckBoxControl').hide();

                    $('.GridViewCheckBoxAll').first().prop('checked', $(this).prop('checked'));
                }
            });
        </script>
    @endpush
@endif
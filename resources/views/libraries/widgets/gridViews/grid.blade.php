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
                @foreach($columns as $column)
                    <th>{{ $column['title'] }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($dataProvider as $row)
                <tr>
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
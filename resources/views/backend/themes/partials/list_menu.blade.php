@foreach($listMenus as $listMenu)
    <div class="media">
        <div class="media-left"></div>
        <div class="media-body">
            <span class="form-control no-border" style="background: transparent">
                <span class="col-sm-6">
                    <span class="input-group">
                        <span class="form-control">
                            {{ $listMenu->getMenuTitle() }}
                        </span>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default EditMenuButton" id="EditMenuButton_{{ $listMenu->id }}" value="{{ action('Backend\ThemeController@editMenu', ['id' => $listMenu->id]) }}"><i class="fa fa-pencil fa-fw"></i></button>
                        </span>
                    </span>
                </span>
            </span>

            <?php
            $listMenu->load(['childrenMenus' => function($query) {
                $query->select('id', 'parent_id', 'name', 'url', 'target_id', 'target')->orderBy('position');
            }]);
            ?>

            @if(count($listMenu->childrenMenus) > 0)

                @include('backend.themes.partials.list_menu', ['listMenus' => $listMenu->childrenMenus])

            @endif

        </div>
    </div>
@endforeach
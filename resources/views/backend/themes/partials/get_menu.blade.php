@if(empty($id))
    <li>
@endif

        <div class="input-group">
            <span class="form-control">{{ $menu->getMenuTitle() }}</span>
            <span class="input-group-btn">
                <button type="button" class="btn btn-default EditMenuButton" id="EditMenuButton_{{ $menu->id }}" value="{{ action('Backend\ThemeController@editMenu', ['id' => $menu->id]) }}"><i class="fa fa-pencil fa-fw"></i></button>
            </span>
            <input type="hidden" name="parent_id[{{ $menu->id }}]" value="{{ $menu->parent_id }}" />
        </div>

@if(empty($id))
    </li>
@endif
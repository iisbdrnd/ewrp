<select required id="menu_id" name="menu_id" class="form-control">
    <option value=""></option>
    @foreach($softwareLinkMenus as $softwareLinkMenu)
    <option value="{{$softwareLinkMenu->id}}">{{$softwareLinkMenu->menu_name}}</option>
    @endforeach
</select>

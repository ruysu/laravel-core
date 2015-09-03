
@if($_notice = Session::get('notice'))
      <div class="alert alert-{{ $_notice[0] }}">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        {{ $_notice[1] }}
      </div>
@endif

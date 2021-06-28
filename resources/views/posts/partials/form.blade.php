   
<form action="{{@$route}}" class="postForm" id="postForm" method="{{$method}}" enctype="multipart/form-data" files="true">
    @csrf
        <input type="hidden" name="id" id="postId" value={{@$model->id}}>
        <input type="hidden" name="user_id" value={{auth()->user()->id}}>
        <input type="hidden" name="image" value={{@$model->image}}>
        Title : <input type='text' name='title' id='title' class='form-control' value="{{@$model->title}}"><br>
        Description : <textarea class="form-control" name="description" rows="4">{{@$model->description}}</textarea><br>
        Select file : <input type='file' name='image'  class='form-control'><br>
        @if (@$model->image)
            <img src={{"/images/".@$model->image}} height="150px" width="150px" class="image" alt="image post">
        @endif
        <div class="mt-2 text-right">
            <button class="btn btn-primary btn-sm shadow-none" data-method="{{@$method}}" data-route="{{@$route}}" id="submit" type="submit">Post comment</button>
        </div>
</form>



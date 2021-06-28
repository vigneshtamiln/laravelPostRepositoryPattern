@extends('layouts.app')
@section('content')<div class="container">
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        @if (auth()->user())
            <div class="panel panel-white profile-widget">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="image-container bg2" style="background:url(http://www.bootdey.com/img/Content/flores-amarillas-wallpaper.jpeg)">  
                            <div class="avatar">{{ucfirst(substr($user->name, 0, 1))}}</div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="details">
                            <h4>{{$user->name}}<i class="fa fa-sheild"></i></h4>
                            <div>{{$user->email}}</div>
                            <div>Total Posts : {{$user->posts->count()}}</div>
                            <div class="mg-top-10">
                                <button type="button" class="btn btn-info" id="addPost" data-toggle="modal" data-target="#createModal">Create Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        @endif
        @if (@$posts->count() <= 0)
            <h4>No Posts Yet..</h2>
        @endif
        @foreach ($posts as $post)
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-white post">
                        <div class="post-heading">
                            @if (auth()->user())
                                <button type="button" class="close" id="postEdit" data-route="{{route('posts.edit', $post->id)}}" data-toggle="modal" data-target="#createModal" data-id={{$post->id}}><i class="fa fa-pencil" aria-hidden="true"></i></button>&nbsp;&nbsp;
                                <button type="button" class="close" id="postDelete" data-route="{{route('posts.destroy', $post->id)}}" data-id={{$post->id}}><i class="fa fa-trash" aria-hidden="true"></i></button>
                            @endif
                            <div class="pull-left image">
                                <div class="avatar_small">{{ucfirst(substr(@$post->user->name, 0, 1))}}</div>
                            </div>
                            <div class="pull-left meta">
                                <div class="title h5">
                                    <a href="#"><b>{{$post->user->name}}</b></a>
                                    uploaded a photo.
                                </div>
                                <h6 class="text-muted time">{{$post->created_at->diffForHumans()}}</h6>
                            </div>
                        </div>
                        <div class="post-image">
                            @php
                                $image = $post->image ? "/images//".$post->image : "https://via.placeholder.com/400x200"; 
                            @endphp
                            <img src=""><img src={{$image}} class="image" alt="image post">
                        </div>
                        <div class="post-description">
                            <h4>{{$post->title}}</h4>
                            <p>{{$post->description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
<!-- Modal -->
<div id="createModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span class="modalTitle"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <span class="loading"></span>
        <div id='createForm'></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '#postDelete', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var route = $(this).data('route');
                $.ajax({
                    method: 'DELETE',
                    url: route,
                    data: "id="+id,
                    success: function (response) {
                        alert("Deleted!", "Your record has been deleted.", "success");
                        location.reload();
                    },
                    error: function (jqXhr) {
                        alert(jqXhr);
                    }
                });
            });
            $('body').on('click', '#postEdit', function (e) {
                e.preventDefault();
                $('.modalTitle').html('Edit Post');
                var id = $(this).data('id');
                var route = $(this).data('route');
                editPost(id, route);
            });
            $('body').on('click', '#addPost', function (e) {
                e.preventDefault();
                $('.modalTitle').html('Create Post');
                addPost();
            });
            $('body').on('submit', '#postForm', function (e) {
                e.preventDefault();
                $.ajax({
                    dataType: "json", // or html if you want...
                    contentType: false, // high importance!
                    url: "{{route('posts.store')}}",
                    method:'POST',
                    data:new FormData(this),
                    processData: false, // high importance!

                     beforeSend: function(){
                        $('.loading').text('Loading...');
                    },
                    complete: function(){
                        $('.loading').text('');
                    },
                    success: function (data) {
                        console.log(data)
                        $('#createModal').modal('hide');
                        location.reload();
                    },
                    error: function (jqXhr) {
                        alert(jqXhr);
                    }
                })
               
            });
        });
        function addPost(){
            $.ajax({
                    method: "GET",
                    url: "{{route('posts.create')}}",
                    data: '',
                    beforeSend: function(){
                        $('.loading').text('Loading...');
                    },
                    complete: function(){
                        $('.loading').text('');
                    },
                    success: function (data) {
                        $('#createForm').html(data)
                    },
                    error: function (jqXhr) {
                        alert(jqXhr);
                    }
                });
        }
        function editPost(id, route){
            $.ajax({
                    method: "GET",
                    url: route,
                    data: id,
                    beforeSend: function(){
                        $('.loading').text('Loading...');
                    },
                    complete: function(){
                        $('.loading').text('');
                    },
                    success: function (data) {
                        $('#createForm').html(data)
                    },
                    error: function (jqXhr) {
                        alert(jqXhr);
                    }
                });
        }
    
    </script>
@endpush




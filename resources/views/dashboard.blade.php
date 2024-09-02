<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, {{ Auth::user()->name }}
        </h2>
    </x-slot>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <a href="#uploadimage" data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-upload"></i> Upload Image
                </a>
            </div>
        </div>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="form-group">
                            <textarea name="description" class="form-control" placeholder="Caption"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100 mb-2">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="container">
        <div class="gallery">
            @foreach ($posts as $post)
                <div class="gallery-item">
                    <div class="card">
                        <div class="card-body">
                            <div class="show_image">
                                <a href="#{{ $post->id }}" data-bs-toggle="modal"><img
                                        src="{{ asset('images/' . $post->image) }}" alt=""></a>
                            </div>
                        </div>
                        <div
                            class="post-footer d-flex justify-content-center align-items-center py-2 border-top bg-light">
                            <div class="button-footer">
                                <a class="btn btn-secondary btn-sm" href="#"><i class="fas fa-comment"></i>
                                    Comment</a>
                                <span class="btn btn-secondary btn-sm">{{ $post->comments()->count() }}</span>
                                <span class="btn btn-secondary btn-sm {{ $post->YouLiked() ? 'liked' : '' }}"
                                    onclick="postlike('{{ $post->id }}',this)"><i class="fas fa-heart"></i>
                                    Like</span>
                                <span class="btn btn-secondary btn-sm"
                                    id="{{ $post->id }}-count">{{ $post->likes()->count() }}</span>
                                @if (Auth::user()->is_admin)
                                    <span class="btn btn-danger btn-sm" onclick="deletePhoto('{{ $post->id }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                <div class="modal fade" id="{{ $post->id }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="show_modal_image">
                                    <a href=""><img src="{{ asset('images/' . $post->image) }}"
                                            alt=""></a>
                                </div>

                                <div class="desc-posts">
                                    <p>{{ $post->description }}</p>
                                </div>


                                <div class="panel-footer" style="border-radius: 12px;">
                                    <span class="user-info">by {{ $post->user->name }}</span>
                                    <span class="user-time">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                <br>
                                <h4><b>Comment:</b></h4>
                                <form action="{{ route('addComment', $post->id) }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <textarea type="text" name="content" class="form-control" placeholder="Comment here"></textarea>
                                    </div><br>
                                    <button class="btn btn-success w-100 mb-2" type="submit">Comment</button>
                                </form>
                                <hr>
                                <div class="comment-list">
                                    @if ($post->comments->isEmpty())
                                        <div class="text-center">No Comment</div>
                                    @else
                                        @foreach ($post->comments as $comment)
                                            <div class="comment-body">
                                                <p>{{ $comment->content }}</p>

                                                <div class="comment-info">
                                                    <span class="btn btn-secondary btn-sm {{ $post->YouLiked() ? 'liked' : '' }}"
                                                        onclick="postlike('{{ $post->id }}',this)"><i class="fas fa-heart"></i>
                                                        Like</span>
                                                    <span class="btn btn-secondary btn-sm"
                                                        id="{{ $post->id }}-count">{{ $post->likes()->count() }}</span>
                                                    <span class="pull-right">
                                                        <span>by {{ $comment->user->name }}</span> |
                                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div> {{--  end form comment --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

<style type="text/css">
    .gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .gallery-item {
        flex: 1 1 calc(33.333% - 10px);
        box-sizing: border-box;
    }

    .show_image {
        width: 100%;
        height: 200px;
        overflow: hidden;
    }

    .show_image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .show_modal_image img {
        width: 100%;
        height: auto;
    }

    .post-footer .button-footer .btn {
        background-color: transparent;
        border: 1px solid #ccc;
        color: #333;
    }

    .post-footer .button-footer .btn:hover {
        background-color: rgba(0, 0, 0, 0.1);
        border-color: #bbb;
    }

    .btn-secondary {
        background-color: transparent;
        border: 1px solid #ffffff;
        color: #ffffff;
    }

    .btn-secondary:hover {
        background-color: rgb(95, 103, 100);
        border-color: #bbb;
    }

    .liked {
        background: #099;
        background-color: #444;
    }

    .desc-posts {
        padding: 14px;
        margin-bottom: 22px;
    }

    .panel-footer {
        display: flex;
        align-items: center;
    }

    .user-time {
        margin-left: auto;
    }

    .comment-body {
        background-color: #4dc4c4;
        color: #ffff;
        padding: 16px;
        border-top-right-radius: 25px;
        border-bottom-left-radius: 20px;
        margin-bottom: 17px;
    }

    .comment-body p {
        font-size: 21px;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;

    }

    .comment-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pull-right {
        margin-left: auto;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
</style>

<script type="text/javascript">
    function postlike(postId, elem) {
        var csrfToken = '{{ csrf_token() }}';
        var likeCount = parseInt($('#' + postId + "-count").text());
        $.post('{{ route('postlike') }}', {
            postId: postId,
            _token: csrfToken
        }, function(data) {
            console.log(data);

            if (data.message === 'liked') {
                $('#' + postId + "-count").text(likeCount + 1);
                $(elem).text('liked').css({
                    color: 'blue'
                });
            } else {
                $('#' + postId + "-count").text(likeCount - 1);
                $(elem).text('liked').css({
                    color: 'red'
                });
            }
        });
    }


    function deletePhoto(postId) {
        if (confirm('Are you sure you want to delete this photo?')) {
            $.ajax({
                url: '/posts/' + postId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.message === 'Photo deleted successfully') {
                        location.reload(); // Reload the page to reflect the changes
                    }
                }
            });
        }
    }
</script>

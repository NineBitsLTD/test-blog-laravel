@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Posts of blog
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <article class="form-group">
                        <div class="pull-right">
                            <a class="btn btn-primary" href="{{ url('admin/blog/create/') }}"><i class="fa fa-plus-circle" title="Add"></i></a>
                        </div>
                        <h3>List of posts</h3>
                        @if (isset($items) && is_array($items) && isset($items['data']) && is_array($items['data']))
                        <div class="list-group">
                            <?php foreach ($items['data'] as $item) { ?>
                            <div class="list-group-item list-group-item-action flex-column align-items-start">
                                @if (isset($item) && file_exists('img/post/'.$item['id']))
                                <img class="img-thumbnail float-left" src="{{ asset('img/post/'.$item['id']) }}">
                                @endif
                                <div class="pull-right">
                                    <a class="btn btn-default" href="{{ url('blog/'.$item['code']) }}"><i class="fa fa-eye" title="view"></i></a>
                                    <a class="btn btn-primary" href="{{ url('admin/blog/edit/'.$item['id']) }}"><i class="fa fa-edit" title="Edit"></i></a>
                                    <a class="btn btn-danger" href="{{ url('admin/blog/delete/'.$item['id']) }}"><i class="fa fa-trash-o" title="Delete"></i></a>
                                </div>
                                <h5 class="mb-1">{{ $item['title'] }}</h5>
                                <small>{{ date("H:i d.m.Y", strtotime($item['created_at'])) }}</small>
                                <p class="mb-1">{{ $item['preview'] }}</p>
                            </div>
                            <?php } ?>
                        </div>
                        @else
                        <h3>Posts not found.</h3>
                        @endif
                    </article>
                    <?= view('layouts.pagination',['items'=>$items]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

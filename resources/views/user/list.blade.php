@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Users
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <article class="form-group">
                        @if (\Auth::user()!=null && \Auth::user()->role_id==1)
                        <div class="pull-right">
                            <a class="btn btn-primary" href="{{ url('admin/user/create/') }}"><i class="fa fa-plus-circle" title="Add"></i></a>
                        </div>
                        @endif
                        <h3>Users list</h3>
                        @if (isset($items) && is_array($items) && isset($items['data']) && is_array($items['data']))
                        <div class="list-group">
                            <?php foreach ($items['data'] as $item) { ?>
                            <div class="list-group-item list-group-item-action flex-column align-items-start">
                                @if (\Auth::user()!=null && \Auth::user()->role_id==1)
                                <div class="pull-right">
                                    <a class="btn btn-primary" href="{{ url('admin/user/edit/'.$item['id']) }}"><i class="fa fa-edit" title="Edit"></i></a>
                                    <a class="btn btn-danger" href="{{ url('admin/user/delete/'.$item['id']) }}"><i class="fa fa-trash-o" title="Delete"></i></a>
                                </div>
                                @endif
                                <h5 class="mb-1">{{ $item['name'] }}</h5>
                                <small>{{ date("H:i d.m.Y", strtotime($item['created_at'])) }}</small>
                                <p class="mb-1">{{ $item['email'] }}</p>
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

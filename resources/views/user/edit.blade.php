@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(isset($item))
                    Edit post
                    @else
                    Create post
                    @endif
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('admin/user/save/'.(isset($item)?$item['id']:'0')) }}">
                        <div class="pull-right">
                            <a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply" title="Cancel"></i></a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"  title="Save"></i></button>
                        </div>
                        <h3>Fields post</h3>
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="name" class="control-label">Name</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name', isset($item['name'])?$item['name']:null) }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="email" class="control-label">E-mail</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', isset($item['email'])?$item['email']:null) }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

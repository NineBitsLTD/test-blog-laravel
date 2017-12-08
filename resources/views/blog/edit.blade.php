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
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ url('admin/blog/save/'.(isset($item)?$item['id']:'0')) }}">
                        <div class="pull-right">
                            <a class="btn btn-default" href="{{ url('admin/blog') }}"><i class="fa fa-reply" title="Cancel"></i></a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"  title="Save"></i></button>
                        </div>
                        <h3>Fields post</h3>
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="title" class="control-label">Title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title', isset($item['title'])?$item['title']:null) }}" required autofocus>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="code" class="control-label">Code</label>
                                <input id="code" type="text" class="form-control" name="code" value="{{ old('code', isset($item['code'])?$item['code']:null) }}" required>
                                @if ($errors->has('code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('preview') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="preview" class="control-label">Preview</label>
                                <input id="preview" type="text" class="form-control" name="preview" value="{{ old('preview', isset($item['preview'])?$item['preview']:null) }}">
                                @if ($errors->has('preview'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('preview') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="text" class="control-label">Content</label>
                                <textarea id="text" class="form-control" name="text" style="height: 150px; resize: vertical;">{{ old('text', isset($item['text'])?$item['text']:null) }}</textarea>
                                @if ($errors->has('preview'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('text') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <label for="image">Image</label>
                                @if (isset($item) && file_exists('img/post/'.$item['id']))
                                <img src="{{ asset('img/post/'.$item['id']) }}" class="img-thumbnail float-left" style="display: block; max-width: 100px; max-height: 100px;"><br>
                                @endif
                                <input id="image" type="file" name="image">
                                @if ($errors->has('image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
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

@extends('layouts.app')

@section('title', 'Create new [[var_singular]]')

@section('content')

{{-- Use method post, action to [[var_plural]] --}}
<form class="form-horizontal" method="POST" action="{{ url('/[[var_plural]]') }}">
<fieldset>

{{-- Required to prevent CSRF --}}
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<!-- Form Name -->
<legend>[[name_model]] Details</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="name">Name</label>  
  <div class="col-md-4">
  <input id="name" name="name" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-8">
    <button id="submit" name="submit" class="btn btn-success">Create</button>
    <a href="{{ url('/[[var_plural]]') }}" class="btn btn-danger">Cancel</a>
  </div>
</div>

</fieldset>
</form>

@endsection
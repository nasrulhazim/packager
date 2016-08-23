@extends('layouts.app')

@section('title', 'Update [[var_singular]]')

@section('content')

{{-- Use method post, action to [[var_plural]] --}}
<form class="form-horizontal" method="POST" action="{{ url('/[[var_plural]]/'.$[[var_singular]]->id) }}">
<fieldset>

{{-- Spoofing method --}}
{{ method_field('PUT') }}

{{ csrf_field() }}

<!-- Form Name -->
<legend>[[name_model]] Details</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="name">Name</label>  
  <div class="col-md-4">
  <input id="name" name="name" type="text" placeholder="" class="form-control input-md" required="" value="{{ $[[var_singular]]->name }}">
    
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-8">
    <button id="submit" name="submit" class="btn btn-success">Update</button>
    <a href="{{ url('/[[var_plural]]') }}" class="btn btn-danger">Cancel</a>
  </div>
</div>

</fieldset>
</form>

@endsection
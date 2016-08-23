@extends('layouts.app')

@section('title', 'Update dummy')

@section('content')

{{-- Use method post, action to dummies --}}
<form class="form-horizontal" method="POST" action="{{ url('/dummies/'.$dummy->id) }}">
<fieldset>

{{-- Spoofing method --}}
{{ method_field('PUT') }}

{{ csrf_field() }}

<!-- Form Name -->
<legend>Dummy Details</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="name">Name</label>  
  <div class="col-md-4">
  <input id="name" name="name" type="text" placeholder="" class="form-control input-md" required="" value="{{ $dummy->name }}">
    
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-8">
    <button id="submit" name="submit" class="btn btn-success">Update</button>
    <a href="{{ url('/dummies') }}" class="btn btn-danger">Cancel</a>
  </div>
</div>

</fieldset>
</form>

@endsection
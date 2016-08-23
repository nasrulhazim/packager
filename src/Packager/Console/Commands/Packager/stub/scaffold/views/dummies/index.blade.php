@extends('layouts.app')

@section('title', '[[name_model]] List')

@section('content')
	<a href="{{ url('/[[var_plural]]/create') }}" class="btn btn-success pull-right">
		<span class="fa fa-plus" aria-hidden="true"></span>
	</a>
	<div class="pull-right">
		{{ $[[var_plural]]->links() }}
	</div>
	<table class="table table-condensed table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			{{-- comment here --}}
			@forelse($[[var_plural]] as $[[var_singular]])
			<tr>
				<td>{{ $[[var_singular]]->name }}</td>
				<td>
					<a href="{{ url('/[[var_plural]]/'.$[[var_singular]]->id) }}" class="btn btn-primary btn-sm">
						<span class="fa fa-search" aria-hidden="true"></span>
					</a>

					<a href="{{ url('/[[var_plural]]/'.$[[var_singular]]->id.'/edit') }}" class="btn btn-warning btn-sm">
						<span class="fa fa-pencil" aria-hidden="true"></span>
					</a>

					<a href="{{ url('/[[var_plural]]/'.$[[var_singular]]->id) }}" class="btn btn-danger btn-sm" 
					data-method="delete" 
					data-token="{{csrf_token()}}" 
					data-confirm="Are you sure?">
						<span class="fa fa-trash" aria-hidden="true"></span>
					</a>

				</td>
			</tr>
			@empty
			<tr>
				<td colspan="2">No [[var_plural]] available</td>
			</tr>
			@endforelse
		</tbody>
	</table>
	<div class="pull-right">
		{{ $[[var_plural]]->links() }}
	</div>
@endsection


@section('script')
	<script type="text/javascript" src="{{ url('js/restful.js') }}"></script>
@endsection




















@extends('layouts.app')

@section('content')

<style type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"></style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
               {{--  <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div> --}}
   {{--  <table id="example" class="display" style="width:100%"> --}}
        <table id="example" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>S.n</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
          @if(!empty($user) && $user->count())
            @foreach($user as $key=>$u)
            <tr id="{{ $key+1 }}">
                <td>{{ $key+ $user->firstItem() }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>@if( $u->status == '1')
                    Active
                    @else
                    InActive
                    @endif
                </td>
            </tr>
          @endforeach
             @else
            <tr>
                <td colspan="4">There are no data.</td>
            </tr>
          @endif
            
        </tbody>
        <tfoot>
            <tr>
                <th>S.n</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
    {!! $user->links() !!}
   </div>
    </div>
</div>
@endsection






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
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $key=>$u)
            <tr id="{{ $key }}">
                <td>{{ $u->email }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->status }}</td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
        </div>
    </div>
</div>
@endsection






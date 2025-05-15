@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Welcome, {{ $user->username }}</h3>
            <p class="card-text">You are logged in as: <strong>{{ ucwords(str_replace('_', ' ', $user->role)) }}</strong></p>

            <div class="mt-4">
                @if(in_array($user->role, ['super_admin', 'admin_logistic']))
                    <a href="{{ route('inventory.index') }}" class="btn btn-success mb-2">Go to Inventory</a>
                    <a href="{{ route('goods_out.index') }}" class="btn btn-primary mb-2">Go to Goods Out</a>
                @endif

                @if(in_array($user->role, ['super_admin', 'admin_mascot', 'admin_costume']))
                    <a href="{{ route('projects.index') }}" class="btn btn-warning mb-2">Go to Projects</a>
                @endif

                @if(in_array($user->role, ['super_admin', 'admin_mascot', 'admin_costume', 'admin_logistic']))
                    <a href="{{ route('material_requests.index') }}" class="btn btn-danger mb-2">Go to Material Requests</a>
                    <a href="{{ route('goods_in.index') }}" class="btn btn-primary mb-2">Go to Goods In</a>
                @endif

                @if(in_array($user->role, ['super_admin', 'admin_finance']))
                    <a href="#" class="btn btn-info mb-2">View Costing Reports</a>
                @endif

                @if(in_array($user->role, ['super_admin', 'admin_finance']))
                    <a href="{{ route('currencies.index') }}" class="btn btn-secondary mb-2">Manage Currencies</a>
                @endif

                @if($user->role === 'super_admin')
                    <a href="{{ route('users.index') }}" class="btn btn-primary mb-2">Manage Users</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

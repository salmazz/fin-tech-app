@extends('layouts.master')

@section('title', 'Wallet')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Welcome, {{ auth()->user()->name }}</h2>
                    </div>

                    <div class="card-body text-center">
                        <h4>Your Current Balance</h4>
                        <h3 class="text-primary">{{ number_format(auth()->user()->wallet->balance, 2) }} USD</h3>

                        <!-- Buttons for Top Up, Transfer, and Withdraw -->
                        <div class="mt-4">
                            <a href="{{ route('wallet.topUp') }}" class="btn btn-success btn-lg mr-3">Top Up</a>
                            <a href="{{ route('wallet.transfer') }}" class="btn btn-primary btn-lg mr-3">Transfer</a>
                            <a href="{{ route('wallet.withdraw') }}" class="btn btn-danger btn-lg">Withdraw</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

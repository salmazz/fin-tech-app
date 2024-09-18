@extends('layouts.master')

@section('title', 'WithDraw')

@section('content')
    <h1>Withdraw from Wallet</h1>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('wallet.withdraw.submit') }}">
        @csrf
        <div class="form-group">
            <label for="balance">Amount</label>
            <input type="number" step="0.01" class="form-control" id="balance" name="amount" value="{{ old('amount') }}"
                   required>
        </div>
        <button type="submit" class="btn btn-primary">Withdraw</button>
    </form>
@endsection

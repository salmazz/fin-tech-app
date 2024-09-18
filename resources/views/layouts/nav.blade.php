<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Recapet App</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('landing-page') }}">Fintech App</a>
            </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
            @endguest
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wallet.topUp') }}">Top Up</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wallet.transfer') }}">Transfer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wallet.withdraw') }}">Withdraw</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions') }}">Transactions</a>
                    </li>
            @endauth
        </ul>
        <ul class="navbar-nav ml-auto">
            @auth
                <li class="nav-item">
                    <span class="navbar-text text-white">Hi, {{ Auth::user()->name }}</span>
                </li>
                <li class="nav-item pr-3">
                    <span class="navbar-text text-white">Balance: ${{ Auth::user()->wallet->balance }}</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>

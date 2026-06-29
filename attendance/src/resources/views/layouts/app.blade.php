<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('title')
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}">
    <link rel="stylesheet" href="{{asset('css/common.css')}}">
    @yield('css')

</head>

<body>
    <header class="header">
        <div class="header-logo">
            <img src="/images/COACHTECHヘッダーロゴ.png" alt="COACHTECH">
        </div>
        <input type="checkbox" id="menu-toggle" class="menu-checkbox">
        <label for="menu-toggle" class="hamburger-btn">
            <span></span>
            <span></span>
            <span></span>
        </label>
        <nav class="header-nav">
            @if (Auth::check())
                <a href="/attendance"><button class="btn-nav">勤怠</button></a>
                <a href="/list"><button class="btn-nav">勤怠一覧</button></a>
                <a href="/applicationList"><button class="btn-nav">申請</button></a>
                <a href="/myreport"><button class="btn-nav">マイ勤怠レポート</button></a>
                <form action="/logout" method="post">
                    @csrf
                    <button class="btn-nav">ログアウト</button>
                </form>
            @endif
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>
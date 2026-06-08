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
            <img src="images/COACHTECHヘッダーロゴ.png" alt="COACHTECH"> 
        </div>
        <nav class="header-nav" >
                    @if (Auth::check())
                    <a href=""><button class="btn-nav">勤怠</button></a>
                    <a href=""><button class="btn-nav">勤怠一覧</button></a>
                    <a href=""><button class="btn-nav">申請</button></a>
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
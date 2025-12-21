<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login · PIXORA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* RESET */
*{
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

/* BODY */
body{
    margin:0;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background:
        radial-gradient(circle at 15% 20%, rgba(236,72,153,.25), transparent 45%),
        radial-gradient(circle at 85% 80%, rgba(168,85,247,.25), transparent 45%),
        linear-gradient(135deg,#fde2f3,#fbcfe8,#e0e7ff);
}

/* CARD */
.card{
    width:420px;
    background:white;
    border-radius:28px;
    padding:35px;
    box-shadow:0 25px 60px rgba(0,0,0,.18);
}

/* TITLE */
.card h2{
    margin:0;
    font-size:28px;
    font-weight:900;
}
.card p{
    margin:6px 0 20px;
    color:#666;
    font-size:15px;
}

/* INPUT */
input{
    width:100%;
    padding:14px 16px;
    margin-top:12px;
    border-radius:14px;
    border:1px solid #ddd;
    font-size:15px;
}
input:focus{
    outline:none;
    border-color:#ec4899;
}

/* BUTTON */
button{
    width:100%;
    margin-top:18px;
    padding:14px;
    border:none;
    border-radius:999px;
    background:#ec4899;
    color:white;
    font-size:16px;
    font-weight:800;
    cursor:pointer;
}
button:hover{
    opacity:.9;
}

/* FOOTER */
.footer{
    text-align:center;
    margin-top:16px;
    font-size:14px;
    color:#666;
}
.footer a{
    color:#ec4899;
    text-decoration:none;
    font-weight:700;
}

/* ERROR */
.error{
    background:#fee2e2;
    color:#b91c1c;
    padding:10px 14px;
    border-radius:12px;
    margin-bottom:12px;
    font-size:14px;
}
</style>
</head>
<body>

<div class="card">
    <h2>Log In</h2>
    <p>Welcome back to <b>PIXORA</b> ✨</p>

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
    @csrf

        <input
            type="email"
            name="email"
            placeholder="Email address"
            value="{{ old('email') }}"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">Login</button>
    </form>

    <div class="footer">
        don’t have an account?
        <a href="/register">register</a>
    </div>
</div>

</body>
</html>

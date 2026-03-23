<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Dashboard</h1>
<p>Welcome to HLA Finder</p>


<form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>
</form>

</body>
</html>
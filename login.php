<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/login_style.css">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login_process.php" method="post">
            <label for="username">Usuário:</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="password">Senha:</label><br>
            <input type="password" id="password" name="password"><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>

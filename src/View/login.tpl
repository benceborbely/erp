<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ERP - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="/public/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/public/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/public/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="/public/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/public/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">

    <form class="form-signin" action="/index.php?route=home" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        {% if msg %}
            <div class="alert alert-danger">
                {{msg}}
            </div>
        {% endif %}
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus oninvalid="this.setCustomValidity('Please enter this field')" oninput="setCustomValidity('')">
        <label for="inputPassword" class="sr-only">Password</label><br/>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required oninvalid="this.setCustomValidity('Please enter this field')" oninput="setCustomValidity('')">
        <img class="form-control" src="{{img}}" /><br/>
        <input type="text" name="code" id="inputCode" class="form-control" placeholder="Please enter the code above" required oninvalid="this.setCustomValidity('Please enter this field')" oninput="setCustomValidity('')"><br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/public/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>

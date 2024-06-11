<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: auto;
            text-align: center;
        }

        .form-signin .form-floating {
            margin-bottom: 10px;
        }

        .form-signin input[type="text"],
        .form-signin input[type="email"],
        .form-signin input[type="password"] {
            padding: 10px;
        }

        .form-signin button[type="submit"] {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<main class="form-signin">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Register</h1>

        <div class="form-floating">
            <input type="text" name="name" class="form-control" id="name" placeholder="Name" required>
            <label for="name">Name</label>
        </div>

        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="email" placeholder="Email address" required>
            <label for="email">Email address</label>
        </div>

        <div class="form-floating">
            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
            <label for="password">Password</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>

        <p class="mt-3">Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </form>
</main>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

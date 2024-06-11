<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 900px; /* Lebar diperbesar untuk menampung tiga kolom */
            padding: 30px;
            margin: auto;
            text-align: center;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-signin .form-floating {
            margin-bottom: 15px;
        }

        .form-signin input[type="text"],
        .form-signin input[type="email"],
        .form-signin input[type="password"] {
            padding: 10px;
            border-radius: 5px;
        }

        .form-signin button[type="submit"] {
            margin-top: 15px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-signin button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-signin .form-floating label {
            font-size: 14px;
            color: #6c757d;
        }

        .form-signin h1 {
            margin-bottom: 20px;
            font-weight: 700;
        }

        .form-signin p {
            font-size: 14px;
            color: #6c757d;
        }

        .form-signin a {
            color: #007bff;
        }

        .form-signin a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<main class="form-signin">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Register</h1>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Name" required>
                    <label for="name"><i class="fas fa-user"></i> Name</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-floating">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email address" required>
                    <label for="email"><i class="fas fa-envelope"></i> Email address</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                </div>
            </div>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>

        <p class="mt-3">Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </form>
</main>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

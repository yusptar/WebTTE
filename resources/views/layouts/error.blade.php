<!-- resources/views/errors/error.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        .container h1 {
            font-size: 6em;
            margin: 0;
        }
        .container p {
            font-size: 1.5em;
        }
        .container a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $exception->getStatusCode() }}</h1>
        <p>{{ $exception->getMessage() ?: 'Oops! An error occurred.' }}</p>
        <a href="{{ url('/') }}">Go to Home</a>
    </div>
</body>
</html>

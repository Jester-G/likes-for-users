<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/pulse/bootstrap.min.css" integrity="sha384-L7+YG8QLqGvxQGffJ6utDKFwmGwtLcCjtwvonVZR/Ba2VzhpMwBz51GaXnUsuYbj" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
<div class="container mt-4">
    <div class="btn-group" role="group">
        <form action="likes.php" method="POST">
            <input type="hidden" name="search" value="from" />
            <button class="btn btn-secondary btn-lg mr-2">Report with sent likes</button>
        </form>
        <form action="likes.php" method="POST">
            <input type="hidden" name="search" value="to" />
            <button class="btn btn-secondary btn-lg">Report with got likes</button>
        </form>
    </div>
</div>

</body>
</html>
<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../src/Models/Thanks.php";
require_once __DIR__ . "/../src/Models/Department.php";

$db = new Database();
$thanks = new Thanks($db->connect());
$department = new Department($db->connect());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_COOKIE['search'])) {
        setcookie('search', $_POST['search']);
    }
}

$search = $_COOKIE['search'];
$page = $_GET['page'] ?? 1;

$pagesCount = $thanks->getPagesCount($search);

$sortByDate = $_GET['daterange'] ?? '';
$sortByDepartment = $_GET['department'] ?? '';

if (!preg_match('/^[1-9]\d*?$/', $page)) {
    $page = 1;
}

if (!preg_match('/^\d{4}\/\d{2}\/\d{2} - \d{4}\/\d{2}\/\d{2}$/', $sortByDate)) {
    $sortByDate = '';
}

if (!preg_match('/^[1-9]\d*?$/', $sortByDepartment)) {
    $sortByDepartment = '';
}

if ($page > $pagesCount) {
    $page = 1;
}

if ($sortByDate && $sortByDepartment) {
    $dateRange = explode(' - ', $sortByDate);

    $start = str_replace('/','-',$dateRange[0]);
    $end = str_replace('/','-',$dateRange[1]);

    $pagesCount = $thanks->getPagesCountForDepartment($search, $sortByDepartment, $start, $end);

    $thanksData = $thanks->getLikesByDepartment($search, $page, $sortByDepartment, $start, $end);
} elseif ($sortByDate) {
    $dateRange = explode(' - ', $sortByDate);

    $start = str_replace('/','-',$dateRange[0]);
    $end = str_replace('/','-',$dateRange[1]);

    $pagesCount = $thanks->getPagesCount($search, $start, $end);

    $thanksData = $thanks->getLikesByDate($search, $page, $start, $end);
} elseif ($sortByDepartment) {
    $thanksData = $thanks->getLikesByDepartment($search, $page, $sortByDepartment);
    $pagesCount = $thanks->getPagesCountForDepartment($search, $sortByDepartment);
} else {
    $thanksData = $thanks->getLikes($search, $page);
}

$likes = $thanksData->fetchAll(\PDO::FETCH_ASSOC);

$departmentData = $department->getDepartment();
$departments = $departmentData->fetchAll(\PDO::FETCH_ASSOC);


?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/pulse/bootstrap.min.css" integrity="sha384-L7+YG8QLqGvxQGffJ6utDKFwmGwtLcCjtwvonVZR/Ba2VzhpMwBz51GaXnUsuYbj" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title></title>
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="height: 10px">
    </nav>

    <div class="d-flex">
        <div class="d-flex justify-content-start mb-2">
            <form method="GET" action="">
                <div class="input-group input-group-sm">
                    <input class="form-control" id="calendar" type="text" name="daterange" value="" />
                    <select class="form-select ml-2" name="department">
                        <option selected disabled>Choose department</option>
                        <?php foreach ($departments as $department): ?>
                        <option value="<?=$department['id']?>"><?=$department['name']?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-sm btn-outline-danger ml-2">Sort</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-secondary">
        <thead>
        <tr>
            <th>Name</th>
            <?php if ($search == 'from'): ?>
            <th>Count of sent likes</th>
            <?php else: ?>
            <th>Count of got likes</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($likes as $like): ?>
            <tr>
                <td><?=$like['name']?></td>
                <td><?=$like['sum']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?php if ($pagesCount > 1): ?>
            <tr>
                <td colspan="2" style="text-align: center">
                    <?php for ($pageNum = 1; $pageNum <= $pagesCount; $pageNum++): ?>
                        <?php if ($pageNum == $page): ?>
                            <b><?=$pageNum?></b>
                        <?php else: ?>
                            <a href="?page=<?=$pageNum?><?=($sortByDate) ? "&daterange=$sortByDate" : '';
                            echo ($sortByDepartment) ? "&department=$sortByDepartment" : '';?>"><?=$pageNum?>
                            </a>
                        <?php endif; ?>
                    <?php endfor;?>
                </td>
            </tr>
        <?php endif ?>
    </table>
    <div class="container mt-2 mb-4">
        <a href="/" class="btn btn-warning ">Go back</a>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(function() {

            $('input[name="daterange"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });
    </script>
</div>
</body>
</html>

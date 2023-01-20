<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Help desk statistics</title>
</head>

<body>
<?php



session_start();
include('database_inc.php');
include('help_navbar.php');
$timezone = new DateTimeZone('Europe/Warsaw');

function getElapsedTime($date) {
    $now = new DateTime();
    $then = new DateTime($date);
    $interval = $now->diff($then);
    $output = "";

    if ($interval->y) $output .= $interval->format("%y years, ");
    if ($interval->m) $output .= $interval->format("%m months, ");
    if ($interval->d) $output .= $interval->format("%d days, ");
    if ($interval->h) $output .= $interval->format("%h hours, ");
    if ($interval->i) $output .= $interval->format("%i minutes, ");
    if ($interval->s) $output .= $interval->format("%s seconds");

    return rtrim($output, ', ');
}




$total_open_records_query = mysqli_query(
    $connect,
    "SELECT COUNT(id) FROM queue WHERE status = 'open' AND academic_year = '$active_year';"
);
while ($result_all_open = mysqli_fetch_row($total_open_records_query)) {
    $count_all_open_all_courses = $result_all_open[0];
}


$total_closed_records_query = mysqli_query(
    $connect,
    "SELECT COUNT(id) FROM queue WHERE status = 'closed' AND academic_year = '$active_year';"
);
while ($result_all_closed = mysqli_fetch_row($total_closed_records_query)) {
    $count_all_closed_all_courses = $result_all_closed[0];
}

$query_most_recent_open_request = mysqli_query(
    $connect,
    "SELECT * FROM queue ORDER BY date_added DESC LIMIT 1;"
);

while ($result_most_recent_open_request = mysqli_fetch_array($query_most_recent_open_request)) {
    $newest_opened = $result_most_recent_open_request['date_added'];
    $newest_opened_id = $result_most_recent_open_request[0];
}


$query_most_recent_closed_request = mysqli_query(
    $connect,
    "SELECT * FROM queue WHERE status = 'closed' ORDER BY date_closed DESC LIMIT 1;"
);

while ($result_most_recent_closed_request = mysqli_fetch_array($query_most_recent_closed_request)) {
    $latest_closed = $result_most_recent_closed_request['date_closed'];
    $latest_closed_id = $result_most_recent_closed_request[0];
}

$query_recently_opened_requests = mysqli_query(
    $connect,
    "SELECT date_added FROM queue WHERE status = 'open' ORDER BY date_added DESC LIMIT 5;"
);
?>





    <div class="container mt-5">
       

        <h5>Total open requests for all courses for this academic year (<?php echo $active_year; ?>): <?php echo $count_all_open_all_courses; ?></h5>
        <h5>Total closed requests for all courses for this academic year (<?php echo $active_year; ?>): <?php echo $count_all_closed_all_courses; ?></h5>
        <h5>Most recent open request <?php echo getElapsedTime($newest_opened); ?></h5>
        <h5>Most recent closed request <?php echo getElapsedTime($latest_closed); ?></h5>
        <h5>Most recent opened requests:<h5>
                <ul>
                    <?php
                    foreach ($foo as $i) {
                        echo "<li>" . $i . " (" . getElapsedTime($i) . ")</li>";
                    }
                    ?>
                </ul>



    </div> <!-- close the container -->

    <?php
    // echo "<pre>";
    // print_r(get_defined_vars());
    // echo "</pre>";
    ?>
    <?php include('footer.php'); ?>





    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>



</body>

</html>
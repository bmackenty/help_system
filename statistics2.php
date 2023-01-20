<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php 
  session_start();
  include('help_navbar.php'); 
  include('database_inc.php');
  $timezone = new DateTimeZone('Europe/Warsaw');

  function time_elapsed_string($datetime, $full = false) {
    $timezone = new DateTimeZone('Europe/Warsaw');
    $now = new DateTime;
    $ago = new DateTime($datetime);
    // $ago = $ago->format('Y-m-d H:i:s');
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }
?>

<div class="mt-3 mb-3"></div>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <?php 
                  $total_open_records_query = mysqli_query($connect,
                  "SELECT COUNT(id) FROM queue WHERE status = 'open';");
          
                    while ($row = mysqli_fetch_row($total_open_records_query)) {
                        echo "<h3> $row[0] </h3>";
                    }
                ?>
                <p>Open requests, all classes</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
              <?php 
                $total_closed_records_query = mysqli_query($connect,
                "SELECT COUNT(id) FROM queue WHERE status = 'closed';");
                while ($row = mysqli_fetch_row($total_closed_records_query)) {
                    echo "<h3>$row[0]</h3>";
                }
              ?>
              <p>Total number of closed requests</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
              <?php
                  $latest_open_record_query = mysqli_query($connect,
                  "SELECT * FROM queue ORDER BY date_added DESC LIMIT 1;");
          
                  while ($row = mysqli_fetch_array($latest_open_record_query))
                  {
                      $newest_opened = $row[3];
                      $timezone = new DateTimeZone('Europe/Warsaw');
                      $newest_opened = DateTime::createFromFormat('m/d/Y H:i', $newest_opened, $timezone);
                      $newest_opened_id = $row[0];
                      echo "<h3>".time_elapsed_string($newest_opened)."</h3>";
                  }
              ?>
            
                <p>Newest open request</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="request_detail.php?id=<?php echo $newest_opened_id; ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
              <?php
                 $latest_closed_record_query = mysqli_query($connect,
                 "SELECT * FROM queue ORDER BY date_closed DESC LIMIT 1;");
         
                 while ($row = mysqli_fetch_array($latest_closed_record_query))
                 {
                    $newest_closed_id = $row[0]; 
                    $date_closed = $row['date_closed'];
                    $date_closed = DateTime::createFromFormat('m/d/Y H:i', $date_closed, $timezone);
                    echo "<h3>".time_elapsed_string($date_closed) ."</h3>";
                 }
              ?>
                <p>Latest closed request</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="request_detail.php?id=<?php echo $newest_closed_id; ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div><!-- close row -->
          <!-- ./col -->
        <div class="row">
          <section class="col-lg-6">
            <!-- TABLE: LATEST HELP REQUESTS -->
            <div class="card card-success">
              <div class="card-header border-transparent">
                <h3 class="card-title">Latest help requests</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Request ID</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th>Priority</th>
                      <th>Posted</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php 
                              $latest_5_opened = mysqli_query($connect,
                              "SELECT * FROM queue ORDER BY date_added DESC LIMIT 5;");
                      
                              while ($row = mysqli_fetch_array($latest_5_opened))
                              { 
                                $latest_5_opened_date = $row['date_added'];
                                ?>
                                <tr>
                                <td><a href="request_detail.php?id=<?php echo $row[0]; ?>"><?php echo $row[0]; ?></a> &nbsp;<small>(<?php echo $row['student']; ?>)</small></td>
                                <td><?php echo substr($row['description'],0,15) . "...."; ?></td>
                                <?php 
                                $status = $row['status'];
                                if ($status == "open") {
                                    ?>
                                    <td><span class="badge badge-success"><?php echo $row['status']; ?></span></td>
                                <?php } elseif ($status == "closed") {
                                    ?>
                                    <td><span class="badge badge-danger"><?php echo $row['status']; ?></span></td>
                                <?php
                                }
                                ?>  
                                <?php 
                                switch($row['priority']){
                                    case 1:
                                        echo "<td>Low</td>";
                                        break;
                                    case 5:
                                        echo "<td>Medium</td>";
                                        break;
                                    case 10:
                                        echo "<td>High</td>";
                                        break;
                                    default:
                                        echo "<td>Normal</td>";
                                    }
                                ?>
                                <td><?php echo time_elapsed_string($latest_5_opened_date); ?>
                              </tr>
                            <?php 
                            }
                            ?>     
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
            </div>
            </section>
            <section class="col-lg-6">
            <!-- BAR CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Requests by Course</h3>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 100%; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
        </div>
        <!-- /.row (main row) -->
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Course Statistics</h3>
                    </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                            <th>Course</th>
                            <th>Open Requests</th>
                            <th>Closed Requests</th>
                            <th>Most recent open</th>
                            <th>Most request closed</th>
                            <th>Time last Request closed</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $query_all_courses = mysqli_query($connect, "SELECT * FROM courses ORDER BY id DESC;");
                        while($row=mysqli_fetch_array($query_all_courses)){
                          $current_course_id = $row['id'];
                        ?>
                          <tr>
                          <td>
                            <a target="_blank" href="view_queue.php?id=<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></a>
                          </td>
                          <td>
                            <?php 
                              $query_total_open = mysqli_query($connect, "SELECT COUNT(id) FROM queue WHERE status = 'open' AND course_id = '$current_course_id';");
                              while($row_total_open=mysqli_fetch_row($query_total_open)){
                                $total_open_requests = $row_total_open[0];
                                echo $total_open_requests;
                              }
                            ?>
                          </td>

                          <td>
                            <?php 
                              $query_total_closed = mysqli_query($connect, "SELECT COUNT(id) FROM queue WHERE status = 'closed' AND course_id = '$current_course_id';");
                              while($row_total_closed=mysqli_fetch_row($query_total_closed)){
                                $total_closed_requests = $row_total_closed[0];
                                echo $total_closed_requests;
                              }
                            ?>
                          </td>

                          <td>
                            <?php 
                              $query_latest_open = mysqli_query($connect,
                              "SELECT * FROM queue WHERE status = 'open' and course_id = '$current_course_id' ORDER BY date_added DESC LIMIT 1;");
                              while ($row_open = mysqli_fetch_row($query_latest_open)) {
                                  $latest_open_for_course = $row_open[3];
                              }
                            ?>
                            <span class="badge badge-info">
                              <?php echo time_elapsed_string($latest_open_for_course); ?>
                            </span>
                          </td>

                          <td>
                            <?php 
                              $query_latest_closed = mysqli_query($connect,
                              "SELECT * FROM queue WHERE status = 'closed' and course_id = '$current_course_id' ORDER BY date_closed DESC LIMIT 1;");
                              while ($row_closed = mysqli_fetch_row($query_latest_closed)) {
                                  $latest_closed_for_course = $row_closed[4];
                              }
                            ?>
                            <span class="badge badge-info">
                              <?php echo time_elapsed_string($latest_closed_for_course); ?>
                            </span>
                          </td>


                          <td>
                            <?php 
                              $date1 = new DateTime($latest_open_for_course);
                              $date2 = new DateTime($latest_closed_for_course);
                              $interval = $date1->diff($date2);
                              $time_to_close =  $interval->d." days " . $interval->h." hours, " . $interval->i." minutes"; 
                            ?>
                            <span class="badge badge-primary"><?php echo $time_to_close; ?></span>
                          </td>




                            <?php } ?>
                        </tbody>
                    </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>


         
        </div>



      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>

<!-- page scripts -->
<script>
  $(function () {
    var areaChartData = {
      labels  : [
      <?php 
      $query_course_name_1 = mysqli_query($connect, "SELECT * FROM courses ORDER BY id DESC;");
      while($row = mysqli_fetch_array($query_course_name_1)){
        ?>
        '<?php echo $row['course_short_name']; ?>',
    <?php 
      }
      ?>
      ],
      datasets: [
        {
          label               : 'Closed',
          backgroundColor     : 'rgba(252, 189, 198,0.9)',
          borderColor         : 'rgba(252, 189, 198,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(252, 189, 198,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(252, 189, 198,1)',
          data                : [<?php 

$query_course_stats = mysqli_query($connect, "SELECT * from courses ORDER BY id DESC;");
while ($row=mysqli_fetch_array($query_course_stats)){
  $current_course_id = $row['id'];

  $query_total_closed = mysqli_query($connect, "SELECT COUNT(id) FROM queue WHERE status = 'closed' AND course_id = '$current_course_id';");
  while($row_total_closed=mysqli_fetch_row($query_total_closed)){
    $total_closed_requests = $row_total_closed[0];
    echo $total_closed_requests.",";
}
}
?>]
        },
        {
          label               : 'Open',
          backgroundColor     : 'rgba(137, 240, 165, 1)',
          borderColor         : 'rgba(137, 240, 165, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(137, 240, 165, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(137, 240, 165,1)',
          data                : [<?php 

$query_course_stats = mysqli_query($connect, "SELECT * from courses ORDER BY id DESC;");
while ($row=mysqli_fetch_array($query_course_stats)){
  $current_course_id = $row['id'];

  $query_total_open = mysqli_query($connect, "SELECT COUNT(id) FROM queue WHERE status = 'open' AND course_id = '$current_course_id';");
  while($row_total_open=mysqli_fetch_row($query_total_open)){
    $total_open_requests = $row_total_open[0];
    echo $total_open_requests.",";
}
}
?>]
        },
      ]
    }
    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })


  })
</script>
<?php 

// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";
?>
</body>
</html>

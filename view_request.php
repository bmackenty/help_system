<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/c3a846c0f5.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="ckeditor/ckeditor.js"></script>
  <link href="ckeditor/plugins/codesnippet/lib/highlight/styles/default.css" rel="stylesheet">
  <script src="ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>


  <title> help: view single request</title>
</head>

<body>
  <?php
  session_start();
  $logged_in_id = $_SESSION['logged_in_id'];
  $logged_in_role = $_SESSION['logged_in_role'];

  function time_elapsed_string($datetime, $full = false)
  {
    $now = new DateTime;
    $ago = new DateTime($datetime);
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

  include('help_navbar.php');
  ?>
  <div class="container mt-5">



    <div class="card card-success">
      <div class="card-header">
        Request Detail for single request
      </div>
      <div class="card-body p-3">
        <p class="card-text">


          <?php
          include('database_inc.php');

          $id = $_GET['id'];
          $id = preg_replace("/[^0-9]/", "", $id);

          $result = mysqli_query(
            $connect,
            "SELECT * FROM queue WHERE id = '$id';"
          );

          if (mysqli_num_rows($result) == 0) {
            $_SESSION['error_invalid_id'] = True;
            header('location:index.php');
          }

          while ($row = mysqli_fetch_array($result)) {
            $date_added = $row['date_added'];
            $date_closed = $row['date_closed'];
            $original_poster = $row['user_id'];
            $course_id = $row['course_id'];
            $anon = $row['anon'];

            $query_course_name = mysqli_query($connect, "SELECT * FROM courses WHERE id = '$course_id';");
            while ($row_course_name = mysqli_fetch_array($query_course_name)) {
              $course_name = $row_course_name['course_name'];
              $course_meeting_notes = $row_course_name['course_meeting_notes'];
            }

            $query_user_name = mysqli_query($connect, "SELECT * FROM users WHERE id = '$original_poster';");
            while ($row_user_name = mysqli_fetch_array($query_user_name)) {
              $user_name = $row_user_name['username'];
            }


          ?>



            <strong>Date this request was submitted:</strong> <?php echo $date_added . " (" . time_elapsed_string($date_added) . ")"; ?>

        <p><strong>Posted by:</strong>
          <?php
            if ($anon && $logged_in_role != "admin") {
              echo "a student";
            } else {
              echo $user_name;
            }
          ?>
        </p>

        <p><strong>Course:</strong> <a href="view_queue.php?id=<?php echo $course_id; ?>"><?php echo $course_name; ?> (<small><?php echo $course_meeting_notes; ?></small>)</a></p>
        <?php
            if (!empty($row['date_closed'])) {
        ?>
          <strong>Date this request was closed:</strong> <?php echo $date_closed . " (" . time_elapsed_string($date_closed) . ")"; ?>
        <?php
            }
        ?>

        <p><strong>Status:</strong>

          <?php $status = $row['status'];
            if ($status == "open") {
          ?>
            <span class="badge badge-success"><?php echo $row['status']; ?></span>
            <?php
              if ($logged_in_id == $original_poster) {
            ?>
              <a href="close_request.php?id=<?php echo $id; ?>">Close this request</a>
            <?php
              }
            } elseif ($status == "closed") {
            ?>
            <span class="badge badge-danger"><?php echo $row['status']; ?></span>
          <?php
            }
          ?>

        </p>

        <p><strong>Priority:</strong>
          <?php switch ($row['priority']) {
              case 1:
                echo "<span class=\"badge badge-success\">Low</span>";
                break;
              case 5:
                echo "<span class=\"badge badge-warning\">Average</span>";
                break;
              case 10:
                echo "<span class=\"badge badge-danger\">High</span>";
                break;
            }
          ?>
        </p>
      </div>
    </div>
    <hr />
    <div class="bg-light p-2 border border-primary rounded">
      <p><strong>Original request for help:</strong></p>

      <?php echo $row['description']; ?>
    </div>
    <hr />
    <?php
            if (!empty($row['teacher_notes'])) {
    ?>
      <p><strong>Teacher Notes:</strong> <textarea class="form-control" rows="10" readonly><?php echo $row['teacher_notes']; ?></textarea></p>
    <?php
            }

            if ($logged_in) {
    ?>


    <?php
            }
            // ===============================
            // This is where we start comments
            // ===============================
            // 
            $current_request = $row['id'];
            $query_child_posts = mysqli_query($connect, "SELECT * from queue_forum WHERE parent_id = '$current_request';");
            if (mysqli_num_rows($query_child_posts) != 0) {
    ?>
      <?php
              while ($result_child_posts = mysqli_fetch_array($query_child_posts)) {
                $likes = $result_child_posts['likes'];
                $rating = $result_child_posts['rating'];
                $report = $result_child_posts['report'];
                $user_id = $result_child_posts['user_id'];
                $query_get_user_name = mysqli_query($connect, "SELECT * FROM users WHERE id = '$user_id';");
                while ($result_user_name = mysqli_fetch_array($query_get_user_name)) {
                  $user_name = $result_user_name['username'];
                  $child_id = $result_child_posts['id'];
                  $user_role = $result_user_name['role'];
                }
      ?>
        <?php
                if ($user_role == "admin") {
        ?>
          <div class="card text-left border border-success">
          <?php
                } elseif ($user_role == "bot") {
          ?>
            <div class="card text-left border border-danger">
            <?php

                } else {
            ?>
              <div class="card text-left">

              <?php
                }
              ?>

              <div class="card-body">
                <p class="card-text"><?php echo $result_child_posts['post']; ?></p>

              </div>
              <div class="card-footer text-muted">
                <span class="float-left">Posted on <?php echo $result_child_posts['date_added']; ?> (<?php echo time_elapsed_string($result_child_posts['date_added']); ?>)
                  by <?php echo $user_name; ?>
                  <?php
                  if ($result_child_posts['user_id'] == $original_poster) {
                  ?>
                    <span class="badge badge-primary">Original poster</span>

                  <?php
                  } elseif ($user_role == "admin") {
                  ?>
                    <span class="badge badge-success">Teacher reply</span>
                  <?php

                  } elseif ($user_role == "bot") {
                  ?>
                    <span class="badge badge-danger">Bot reply</span>
                  <?php
                  }
                  ?>
                </span>
                <?php if ($logged_in) {
                ?>
                  <span class="float-right">
                    <?php if ($likes == 0) {
                    ?>
                      <a href="process_like.php?id=<?php echo $child_id; ?>&return_id=<?php echo $id; ?>">Be the first to like</a>
                    <?php
                    } elseif ($likes == 1) {
                    ?>
                      <?php echo $likes; ?> <a href="process_like.php?id=<?php echo $child_id; ?>&return_id=<?php echo $id; ?>">like</a>
                    <?php
                    } else {
                    ?>
                      <?php echo $likes; ?> <a href="process_like.php?id=<?php echo $child_id; ?>&return_id=<?php echo $id; ?>">likes</a>
                    <?php
                    }
                    ?>
                  </span>
                <?php } ?>
              </div>
              </div>
          <?php
              }
            } // end conditional for child posts
          }
          // echo "<pre>";
          // print_r(get_defined_vars());
          // echo "</pre>";
          if ($logged_in) {
          ?>

          <form action="queue_post_reply.php" method="POST">
            <div class="form-group">
              <label for="reply">Type your reply here:</label>
              <textarea class="form-control" name="reply" id="problem" rows="3"></textarea>
            </div>
            <input type="hidden" name="parent_post" value="<?php echo $id; ?>">

            <button type="submit" class="btn btn-primary">Submit reply</button>
          </form>

        <?php } else { ?>
          <div class="callout callout-info">
            <h5>Login to reply</h5>
            <p>If you were <a class="alert-link" href="login.php" target="_new">logged in</a> you could reply!</p>
          </div>
        <?php } ?>

            </div> <!-- close the container -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
            </script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
            </script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
            </script>
            <script>
              CKEDITOR.replace('problem');
            </script>
            <script>
              hljs.initHighlightingOnLoad();
            </script>

</body>

</html>
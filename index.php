<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="ckeditor/ckeditor.js"></script>

</head>

<body>
  <?php
  session_start();
  include('database_inc.php');
  include('help_navbar.php');

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
  ?>
  <div class="container mt-5">
    <?php
    if ($_SESSION['logged_in']) {
    ?>
      <div class="alert alert-primary" role="alert">
        Thank you for asking for help. I use a first-in-first-out system to help students.
        Please use the navigation bar to select your course and see where your request is in the queue.
      </div>

      <div class="row">
        <?php
        $id_of_logged_in_user = $_SESSION['logged_in_id'];
        $query_my_tickets = mysqli_query($connect, "SELECT * FROM queue WHERE user_id = '$id_of_logged_in_user';");
        if (mysqli_num_rows($query_my_tickets) == 0) {
        ?>
      </div>
    <?php
        } else {
    ?>
  </div>
  <div class="alert alert-info mt-3" role="alert">
    These are all your help requests, open and closed. You can create a new help request by scrolling down.
  </div>

  <table id="myTable" class="table table-hover">
    <thead>
      <tr>
        <th>Status</th>
        <th>Created</th>
        <th>Snippet</th>
        <th>Priority</th>
        <th>Replies</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
          while ($result_my_tickets = mysqli_fetch_array($query_my_tickets)) {
            $current_id = $result_my_tickets['id'];
            $query_get_replies = mysqli_query($connect, "SELECT * FROM queue_forum WHERE parent_id = '$current_id';");
            $reply_count = mysqli_num_rows($query_get_replies);
            $status = $result_my_tickets['status'];
      ?>
        <tr>
          <td>
            <?php
            if ($status == "open") {
            ?>
              <span class="badge badge-success">Open</span>
            <?php } else { ?>
              <span class="badge badge-danger">Closed</span>
            <?php } ?>



          </td>

          <td><?php echo time_elapsed_string($result_my_tickets['date_added']); ?></td>
          <td><a href="view_request.php?id=<?php echo $result_my_tickets['id']; ?>">
              <?php
              $description = $result_my_tickets['description'];
              $description = strip_tags($description);
              echo substr($description, 0, 30) . "..."; ?></a></td>
          <td>
            <?php
            $priority = $result_my_tickets['priority'];
            switch ($priority) {
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
          </td>

          <td><a href="view_request.php?id=<?php echo $result_my_tickets['id']; ?>"><?php echo $reply_count; ?></a></td>
          <td><?php if ($status != "closed") {
              ?>

              <a href="close_request.php?id=<?php echo $result_my_tickets['id']; ?>">Close this request</a>
            <?php } ?>
          </td>

        </tr>
    <?php
          }
        }  // end while loop with results
    ?>
    </tbody>
  </table>

  <div class="alert alert-info mt-3" role="alert">
    Create a new help request.
  </div>

  <form action="add_to_queue.php" method="POST" id="new_help_request">
    <div class="form-group">

      <input required name="first_name" type="text" class="form-control" id="first_name" placeholder="Please enter your FIRST NAME only. No last names." value="<?php echo $_SESSION['logged_in_user']; ?>" readonly>
    </div>

    <div class="form-group">
      <label for="block">Please choose your course</label>
      <select name="course_id" class="form-control" id="block" required>
        <?php
        $course_query = mysqli_query($connect, "SELECT * FROM courses;");
        ?>
        <?php
        while ($row = mysqli_fetch_array($course_query)) {
          echo $row['course_name'];
        ?>

          <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name'] . "<small> (" . $row['course_meeting_notes'] . ")</small>"; ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="form-group">
      <label for="block">How is this problem impacting your progress?</label>
      <select name="priority" class="form-control" id="block" required>
        <option value="1">I can keep working with this problem</option>
        <option value="5">This problem is keeping me from completing my project but I can still get some work done </option>
        <option value="10">I can't get anything done because of this problem</option>
      </select>
    </div>



    <div class="form-group">
      <label for="problem"><strong>Describe</strong> your problem</label>
      <a id="collapseText" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        <small>(show what is a good question)</small>
      </a>
      <div class="collapse" id="collapseExample">
        <div class="card card-body">
          <p>If you ask a good question, you get a good answer. The secret of asking good questions
            is to be REALLY specific about what you want to know and demonstrate you have done some work to solve your problem.</p>

          <p>Here's a list of the most horrible questions ever asked in the history of humanity:</p>
          <ul>
            <li>This doesn't work. How do I make this work?</li>
            <li>This is broken. How do I fix it?</li>
            <li>I don't understand what's wrong. How do I do the thing?</li>
          </ul>

          <p>The characteristics of better questions:</p>

          <ul>
            <li>Very specifically describe what isn't working</li>
            <li>Very specifically describe what you have tried</li>
            <li>Often include error messages</li>
            <li>Share the code that isn't working</li>
            <li>Include screenshots of the problem</li>
            <li>Very clearly explain your goal</li>
          </ul>
        </div>
        <br /><br />
      </div>
      <textarea name="description" class="form-control" id="problem" rows="7" placeholder="Please describe your issue in this textbox." required></textarea>
    </div>

    <div class="form-group form-check">
      <input name="anon" type="checkbox" class="form-check-input" id="check_anon" value="anon">
      <label class="form-check-label" for="check_anon">Make this request anonymous to everyone except our teacher</label>
    </div>
    <button type="submit" class="btn btn-primary">Click here to ask for help</button>

    </div>
  </form>

  </div>
<?php } else {

?>
  <div class="alert alert-primary" role="alert">
    <h3>Hello and welcome. </h3>
    <p>This website is used for students to ask for help.</p>
    <p>Please <a class="alert-link" href="register_student.php">register</a> and / or <a class="alert-link" href="login.php">login</a> to request help.
  </div>



<?php } ?>
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

<script>
  $('#collapseExample').on('hidden.bs.collapse', function() {
    document.getElementById('collapseText').innerHTML = "<small>(show what is a good question)</small>";
  })
  $('#collapseExample').on('shown.bs.collapse', function() {
    document.getElementById('collapseText').innerHTML = "<small>(hide what is a good question)</small>";
  })
</script>

<script>
  // Replace the <textarea id="editor1"> with a CKEditor 4
  // instance, using default configuration.
  CKEDITOR.replace('problem');
</script>

</body>

</html>
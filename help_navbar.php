<?php include('database_inc.php'); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="/">Ask for help</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbarNavDropdown" class="navbar-collapse collapse">
            <ul class="navbar-nav mr-auto">
             
                    <li class="nav-item">
                        <a class="nav-link" href="statistics.php">Statistics</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       View courses
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php 
                        $course_query = mysqli_query($connect, "SELECT * FROM courses ORDER BY FIELD(ID,2,7,1,3,4,5,6);");
                        while($row = mysqli_fetch_array($course_query)){
                        ?>
                        <a class="dropdown-item" href="view_queue.php?id=<?php echo $row['id']; ?>"><?php echo $row['course_name'] . "<small> (". $row['course_meeting_notes'].")</small>"; ?></a>
                        <?php } ?>
   
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <?php 
                    if (!isset($_SESSION)){
                        session_start();
                    }
                    if (isset($_SESSION['logged_in_user'])){
                        $logged_in_user = $_SESSION['logged_in_user'];
                    } 
                    ?>
                </ul>
                <ul class="navbar-nav">
                <?php
                if(!empty($logged_in) && $logged_in_user == "bmackenty") {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Admin &nbsp; | </a>
                        </li>
                        <?php
                        }
                        ?>

                    <?php 
                        if (isset($_SESSION['logged_in'])){
                            $logged_in = $_SESSION['logged_in'];
                            $logged_in_user = $_SESSION['logged_in_user'];
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user_profile.php" target="_new"><?php echo $logged_in_user; ?>&nbsp;  |</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout &nbsp;  | </a>
                            </li>
                        <?php 
                        } else {
                            ?>
                               <li class="nav-item">
                                <a class="nav-link" href="login.php">Login &nbsp; | </a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" href="register_student.php">Register &nbsp; | </a>
                                </li>
                            <?php 
                        }
                    ?>
                <?php
                $query_active_year = mysqli_query($connect,"SELECT description FROM academic_year WHERE status = 'active';");
                while($row_active_year = mysqli_fetch_array($query_active_year)){
                    $active_year = $row_active_year['description'];
                }
                ?>
                <span class="navbar-text"><li class="nav-item">Active year: <?php echo $active_year; ?></li></span>
                </ul>
        </div>
    </nav>
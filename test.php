<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="ckeditor/ckeditor.js"></script>
    <title> help: view single request</title>
</head>
<body>
    <form action="queue_post_reply.php" method="POST">
        <div class="form-group">
            <label for="reply">Type your reply here:</label>
            <textarea class="form-control" name="reply" id="problem" rows="3"></textarea>
        </div>
       

        <button type="submit" class="btn btn-primary">Submit reply</button>
    </form>
    <script>
        CKEDITOR.replace( 'problem' );
    </script>
    </body>
    </html>
<?php
include('includes/header.php');
?>

      <link rel="stylesheet" href="password.css">
      <title>Change Password</title>

       <h1>Change Password</h1>

        <form action="update_password.php" method="POST">

        <div class="old">
            <label for="old">Old Password</label>
            <input type="password" name="first" id="old" required>
        </div>

        <div class="new">
            <label for="new">Create New Password</label>
            <input type="password" name="next" id="new" required>
        </div>

        <div class="confirm">
            <label for="confirm">Confirm New Password</label>
            <input type="password" name="final" id="confirm" required >
        </div>

        <div class="final">
            <input type="submit" value="Update Password">
        </div>

</form>

<?php
include('includes/footer.php');
?>
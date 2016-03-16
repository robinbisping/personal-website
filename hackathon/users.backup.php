<?php
include('./classes/database.class.php');
include('./classes/gump.class.php');
include('./classes/user.class.php');

switch ($_GET['request']) {
    case 'get_id':
        if($_GET['user_id'] != "" && $_GET['user_id'] > 0) {
            $results = user::from_id($_GET['user_id']);
        }
        break;
    case 'get_all':
          $results = user::all();
        break;
    case 'post_karma':
        print_r('done');
        if($_POST['user_id'] != "" && $_POST['user_id'] > 0 && $_POST['karma_value'] != "" && $_POST['karma_value'] > 0) {
            $user = user::from_id($_POST['user_id']);
            $user->karma = $user->karma + $_POST['karma_value'];
            $user->save();
            $user = null;
        }
        break;
}

switch ($_GET['request']) {
    case 'register':
      $is_valid = GUMP::is_valid($_POST, array(
          'email' => 'required|valid_email',
          'password' => 'required|max_len,100|min_len,4',
          'firstname' => 'required|valid_name',
          'lastname' => 'required|valid_name',
          'role' => 'required|contains,buddy user'
      ));
      if($is_valid === true) {
          $user = new user();
          $user->email = $_POST['email'];
          $user->password = $_POST['password'];
          $user->firstname = $_POST['firstname'];
          $user->lastname = $_POST['lastname'];
          $user->status = "active";
          $user->role = $_POST['role'];
          $user->registered = date('Y-m-d H:i:s');
          $user->create();
      } else {
          print_r($is_valid);
      }
      break;
    case 'post_karma':
        print_r('done');
        if($_POST['user_id'] != "" && $_POST['user_id'] > 0 && $_POST['karma_value'] != "" && $_POST['karma_value'] > 0) {
            $user = user::from_id($_POST['user_id']);
            $user->karma = $user->karma + $_POST['karma_value'];
            $user->save();
            $user = null;
        }
        break;
}


if($results != "") {
  echo json_encode($results, JSON_PRETTY_PRINT);
} else {
  echo "Nothing found.";
}

?>
<form method="post" action="https://www.robinbisping.com/hackathon/users.php?request=register">
<input type="text" name="email">
<input type="text" name="password">
<input type="text" name="firstname">
<input type="text" name="lastname">
<input type="text" name="role">
<input type="submit" value="register">
</form>

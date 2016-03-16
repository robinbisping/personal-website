<?php
include('./classes/database.class.php');
include('./classes/gump.class.php');
include('./classes/user.class.php');
include('./classes/match.class.php');

switch ($_GET['action']) {
    case 'register':
        $is_valid = GUMP::is_valid($_POST, array(
            'email' => 'required|valid_email',
            'password' => 'required|max_len,100|min_len,4',
            'firstname' => 'required|valid_name',
            'lastname' => 'required|valid_name',
        ));
        if($is_valid === true) {
            $user = new user();
            $user->email = $_POST['email'];
            $user->password = $_POST['password'];
            $user->firstname = $_POST['firstname'];
            $user->lastname = $_POST['lastname'];
            $user->status = "active";
            $user->registered = date('Y-m-d H:i:s');
            $user->create();
            echo 1;
        } else {
            echo 0;
        }
        break;
    case 'login':
        $is_valid = GUMP::is_valid($_POST, array(
            'email' => 'required|valid_email',
            'password' => 'required|max_len,100|min_len,4',
        ));
        if($is_valid === true) {
            $user = user::from_email($_POST['email']);
            if($user->password == $_POST['password']) {
              $user->last_login = date('Y-m-d H:i:s');
              $user->save();
              echo json_encode($user, JSON_PRETTY_PRINT);
            } else {
              echo 0;
            }
        } else {
            echo 0;
        }
        break;
    case 'user_from_id':
        $is_valid = GUMP::is_valid($_POST, array(
            'user_id' => 'required|integer'
        ));
        if($is_valid === true) {
            $user = user::from_id($_POST['user_id']);
            echo json_encode($user, JSON_PRETTY_PRINT);
        } else {
            echo 0;
        }
        break;
    case 'change_karma':
        $is_valid = GUMP::is_valid($_POST, array(
            'user_id' => 'required|integer|max_len,6',
            'karma' => 'required|integer|max_len,6'
        ));
        if($is_valid === true) {
            $user = user::from_id($_POST['user_id']);
            $user->karma = $user->karma + $_POST['karma'];
            $user->save();
            echo $user->karma;
        } else {
            echo 0;
        }
        break;
    case 'coordinates':
        $is_valid = GUMP::is_valid($_POST, array(
            'user_id' => 'required|integer|max_len,6',
            'user_latitude' => 'float',
            'user_longitude' => 'float',
            'buddy_id' => 'required|integer|max_len,6',
            'buddy_latitude' => 'float',
            'buddy_longitude' => 'float'
        ));
        if($is_valid === true) {
            if(!match::check_user_id($_POST['user_id']) && !match::check_buddy_id($_POST['buddy_id'])) {
                $match = new match;
                if($_POST['user_latitude'] != "") {
                  $match->user_id = $_POST['user_id'];
                  $match->user_latitude = floatval($_POST['user_latitude']);
                  $match->user_longitude = floatval($_POST['user_longitude']);
                  $match->create_user();
                } elseif($_POST['buddy_latitude'] != "") {
                  $match->buddy_id = $_POST['buddy_id'];
                  $match->buddy_latitude = floatval($_POST['buddy_latitude']);
                  $match->buddy_longitude = floatval($_POST['buddy_longitude']);
                  $match->create_buddy();
                }
            } elseif(!match::check_user_id($_POST['user_id']) && match::check_buddy_id($_POST['buddy_id'])) {
                $match = match::from_buddy_id($_POST['buddy_id']);
                if($_POST['user_latitude'] != "") {
                  $match->user_id = $_POST['user_id'];
                  $match->user_latitude = floatval($_POST['user_latitude']);
                  $match->user_longitude = floatval($_POST['user_longitude']);
                  $match->save();
                }
            } elseif(match::check_user_id($_POST['user_id']) && !match::check_buddy_id($_POST['buddy_id'])) {
                $match = match::from_user_id($_POST['user_id']);
                if($_POST['buddy_latitude'] != "") {
                  $match->buddy_id = $_POST['buddy_id'];
                  $match->buddy_latitude = floatval($_POST['buddy_latitude']);
                  $match->buddy_longitude = floatval($_POST['buddy_longitude']);
                  $match->save();
                }
            }
        } else {
            print_r($is_valid);
        }
        break;
    case 'distance':
        $is_valid = GUMP::is_valid($_POST, array(
            'user_id' => 'required|integer|max_len,6',
            'buddy_id' => 'required|integer|max_len,6'
        ));
        if($is_valid === true) {
            $user = match::from_user_id($_POST['user_id']);
            if($user->id == match::from_buddy_id($_POST['buddy_id'])->id) {
              $distance = match::distance($user->user_latitude, $user->user_longitude, $user->buddy_latitude, $user->buddy_longitude);
              echo $distance;
            } else {
              echo 0;
            }
        } else {
            echo 0;
        }
        break;
}
?>

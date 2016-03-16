<?php

class match {

	public $id;
	public $user_id;
	public $user_latitude;
  public $user_longitude;
  public $buddy_id;
	public $buddy_latitude;
  public $buddy_longitude;

	function __construct($m = null) {
		if(isset($m) && count($m)) {
			$this->populate($m);
		}
	}

	function populate(array $m) {
		$this->id = (int) $m["id"];
		$this->user_id = (int) $m["user_id"];
		$this->user_latitude = (double) $m["user_latitude"];
    $this->user_longitude = (double) $m["user_longitude"];
    $this->buddy_id = (int) $m["buddy_id"];
		$this->buddy_latitude = (double) $m["buddy_latitude"];
    $this->buddy_longitude = (double) $m["buddy_longitude"];
	}

	public static function from_id($id) {
		$database = new Database();
		$database->query('SELECT id, user_id, user_latitude, user_longitude, buddy_id, buddy_latitude, buddy_longitude FROM matches WHERE id = :id');
		$database->bind(':id', $id);
		$row  = $database->single();
		$match = new self($row);
		return $match;
	}

  public static function from_user_id($user_id) {
    $database = new Database();
    $database->query('SELECT id, user_id, user_latitude, user_longitude, buddy_id, buddy_latitude, buddy_longitude FROM matches WHERE user_id = :user_id');
    $database->bind(':user_id', $user_id);
    $row  = $database->single();
    $match = new self($row);
    return $match;
  }

  public static function from_buddy_id($buddy_id) {
		$database = new Database();
		$database->query('SELECT id, user_id, user_latitude, user_longitude, buddy_id, buddy_latitude, buddy_longitude FROM matches WHERE buddy_id = :buddy_id');
		$database->bind(':buddy_id', $buddy_id);
		$row  = $database->single();
		$match = new self($row);
		return $match;
	}

  public static function check_user_id($user_id) {
    $database = new Database();
    $database->query('SELECT id FROM matches WHERE user_id = :user_id');
    $database->bind(':user_id', $user_id);
    $database->execute();
    $rowcount  = $database->rowCount();
    if($rowcount > 0) {
      return true;
    } else {
      return false;
    }
  }

  public static function check_buddy_id($buddy_id) {
    $database = new Database();
    $database->query('SELECT id FROM matches WHERE buddy_id = :buddy_id');
    $database->bind(':buddy_id', $buddy_id);
    $database->execute();
    $rowcount = $database->rowCount();
    if($rowcount > 0) {
      return true;
    } else {
      return false;
    }
  }

  function create_user() {
		$database = new Database();
		$database->query('INSERT INTO matches(user_id, user_latitude, user_longitude) VALUES (:user_id, :user_latitude, :user_longitude)');
		$database->bind(':user_id', $this->user_id);
		$database->bind(':user_latitude', $this->user_latitude);
		$database->bind(':user_longitude', $this->user_longitude);
		$database->execute();
	}

  function create_buddy() {
		$database = new Database();
		$database->query('INSERT INTO matches(buddy_id, buddy_latitude, buddy_longitude) VALUES (:buddy_id, :buddy_latitude, :buddy_longitude)');
		$database->bind(':buddy_id', $this->buddy_id);
		$database->bind(':buddy_latitude', $this->buddy_latitude);
		$database->bind(':buddy_longitude', $this->buddy_longitude);
		$database->execute();
	}

  function save(){
    $database = new Database();
    $database->query('UPDATE matches SET user_id = :user_id, user_latitude = :user_latitude, user_longitude = :user_longitude, buddy_id = :buddy_id, buddy_latitude = :buddy_latitude, buddy_longitude = :buddy_longitude WHERE id = :id');
    $database->bind(':id', $this->id);
    $database->bind(':user_id', $this->user_id);
		$database->bind(':user_latitude', $this->user_latitude);
		$database->bind(':user_longitude', $this->user_longitude);
    $database->bind(':buddy_id', $this->buddy_id);
		$database->bind(':buddy_latitude', $this->buddy_latitude);
		$database->bind(':buddy_longitude', $this->buddy_longitude);
    $database->execute();
  }

  public static function distance($latitute1, $longitute1, $latitute2, $longitute2) {
    $theta = $longitute1 - $longitute2;
    $distance = sin(deg2rad($latitute1)) * sin(deg2rad($latitute2)) +  cos(deg2rad($latitute1)) * cos(deg2rad($latitute2)) * cos(deg2rad($theta));
    $distance = acos($distance);
    $distance = rad2deg($distance);
    $distance = $distance * 60000 * 1.1515 * 1.609344;
    return (String) $distance;
  }

	function delete() {
		$database = new Database();
		$database->query('DELETE FROM matches WHERE id = :id');
		$database->bind(':id', $this->id);
		$database->execute();
	}
}
?>

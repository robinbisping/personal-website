<?php

class user {

	public $id;
	public $email;
	public $password;
	public $firstname;
	public $lastname;
	public $karma;
  public $status;
	public $last_login;
  public $registered;

	function __construct($m = null) {
		if(isset($m) && count($m)) {
			$this->populate($m);
		}
	}

	function populate(array $m) {
		$this->id    			= (int) $m["id"];
		$this->email      = (string) $m["email"];
		$this->password   = (string) $m["password"];
		$this->firstname  = (string) $m["firstname"];
		$this->lastname   = (string) $m["lastname"];
		$this->karma      = (int) $m["karma"];
    $this->status     = (string) $m["status"];
		$this->last_login = (string) $m["last_login"];
    $this->registered = (string) $m["registered"];
	}

	public static function from_id($id) {
		$user     = null;
		$database = new Database();
		$database->query('SELECT id, email, password, firstname, lastname, karma, status, last_login, registered FROM users WHERE id = :id');
		$database->bind(':id', $id);
		$row  = $database->single();
		$user = new self($row);
		return $user;
	}

	public static function from_email($email) {
		$user = null;
		$database = new Database();
		$database->query('SELECT id, email, password, firstname, lastname, karma, status, last_login, registered FROM users WHERE email = :email');
		$database->bind(':email', $email);
		$row  = $database->single();
		$user = new self($row);
		return $user;
	}

	public static function all() {
		$database = new Database();
		$database->query('SELECT id, email, password, firstname, lastname, karma, status, last_login, registered FROM users');
		$rows = $database->resultset();
		return $rows;
	}

	function create() {
		$database = new Database();
		$database->query('INSERT INTO users(email, password, firstname, lastname, status, registered) VALUES (:email, :password, :firstname, :lastname, :status, :registered)');
		$database->bind(':email', $this->email);
		$database->bind(':password', $this->password);
		$database->bind(':firstname', $this->firstname);
		$database->bind(':lastname', $this->lastname);
		$database->bind(':status', $this->status);
		$database->bind(':registered', $this->registered);
		$database->execute();
	}

	function save(){
		$database = new Database();
		$database->query('UPDATE users SET email = :email, password = :password, firstname = :firstname, lastname = :lastname, karma = :karma, status = :status, last_login = :last_login, registered = :registered WHERE id = :id');
		$database->bind(':id', $this->id);
		$database->bind(':email', $this->email);
		$database->bind(':password', $this->password);
		$database->bind(':firstname', $this->firstname);
		$database->bind(':lastname', $this->lastname);
		$database->bind(':karma', $this->karma);
		$database->bind(':status', $this->status);
		$database->bind(':last_login', $this->last_login);
		$database->bind(':registered', $this->registered);
		$database->execute();
	}

	function delete() {
		$database = new Database();
		$database->query('DELETE FROM users WHERE id = :id');
		$database->bind(':id', $this->id);
		$database->execute();
	}

}
?>

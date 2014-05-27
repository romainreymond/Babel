<?php 
class General{

    private $usr;
	private $db;
    
    public function __construct($users, $database) {
	    $this->usr = $users;
		$this->db = $database;
	}

    /* Check si l'utilisateur est connecter sur la session php. */
	public function logged_in () {
        $id = $this->usr->rememberme();
        if (isset($id)) {
            if (!isset($_SESSION['id'])) {
                $_SESSION['id'] = $id;
            }
			if (!isset($_SESSION['admin'])) {
				$_SESSION['admin'] = intval($this->usr->is_admin($id));
			}
			if (!isset($_SESSION['admin_connect'])) {
				$_SESSION['admin_connect'] = 0;
			}
            return true;
        }
		if (isset($_SESSION['id'])) {
			if (!isset($_SESSION['admin'])) {
				$_SESSION['admin'] = intval($this->usr->is_admin($_SESSION['id']));
			}
			if (!isset($_SESSION['admin_connect'])) {
				$_SESSION['admin_connect'] = 0;
			}
			return true;
		}
		return false;
	} 
	/* Si l'utilisateur est connecter sur la session php ou si il a le cookie rememberme il est renvoyer a l'accueil */
	public function logged_in_protect() {
		if ($this->logged_in() === true) {
			header('Location: ' . VIEW_IND);
			exit();		
		}
    }
	/* Si l'utilisateur n'est pas connecter sur la session php ou si il n'a pas le cookie rememberme il est renvoyer a la page de connection */
	public function logged_out_protect() {
		if ($this->logged_in() === false) {
			header('Location: ' . HTTP_BASE . 'login.php');
			exit();
		}	
	}
	
	public function add_notif ($to_user, $from_user, $ref_id, $type) {
		$date = date('Y-m-d');
		$query = $this->db->prepare("INSERT INTO `notifs` (`to_user`, `from_user`, `ref_id`, `type`, `date`) VALUES (?, ?, ?, ?, ?) ");
		$query->bindValue(1, $to_user);
		$query->bindValue(2, $from_user);
		$query->bindValue(3, $ref_id);
		$query->bindValue(4, $type);
		$query->bindValue(5, $date);
		try{
            $query->execute();
			return true;
		}
        catch(PDOException $e) {
            die($e->getMessage());
		}
	}
	
	public function get_notifs($to_user) {
		$query = $this->db->prepare("SELECT * FROM `notifs` WHERE `to_user`=? ORDER BY `date` DESC, `id` DESC");
		$query->bindValue(1, $to_user);
        try{
            $query->execute();
			$rows = $query->fetchAll();
			$query->closeCursor();
			return $rows;
        }
        catch(PDOException $e) {
            die($e->getMessage());
		}
	}
	
	public function get_nb_new_notifs($to_user) {
		$query = $this->db->prepare("SELECT * FROM `notifs` WHERE `to_user`=? AND `seen`='0'");
		$query->bindValue(1, $to_user);
        try{
            $query->execute();
			$nb = count($query->fetchAll());
			$query->closeCursor();
			return $nb;
        }
        catch(PDOException $e) {
            die($e->getMessage());
		}
	}
	
	public function update_notifs($ref_id, $to_user) {
		$query 	= $this->db->prepare("UPDATE `notifs` SET `seen`='1' WHERE `ref_id`= ? AND `to_user` = ?");
        $query->bindValue(1, $ref_id);
        $query->bindValue(2, $to_user);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
	}
	
	public function del_notif_by_ref ($ref_id, $from_user, $date) {
		$query = $this->db->prepare("DELETE FROM `notifs` WHERE `ref_id` = ? AND `from_user` = ? AND `date` = ?");
		$query->bindValue(1, $ref_id);
		$query->bindValue(2, $from_user);
		$query->bindValue(3, $date);
		try{
            $query->execute();
			return true;
		}
        catch(PDOException $e) {
            die($e->getMessage());
			return false;
		}
		return false;
	}
	
	public function del_notif_by_id ($id) {
		$query = $this->db->prepare("DELETE FROM `notifs` WHERE `id` = ?");
		$query->bindValue(1, $id);
		try{
            $query->execute();
			return true;
		}
        catch(PDOException $e) {
            die($e->getMessage());
			return false;
		}
		return false;
	}
	
	public function del_notifs($to_user) {
		$date = date('Y-m-d', strtotime('-15 days'));
		$query = $this->db->prepare("DELETE FROM `notifs` WHERE `date` < ? AND `to_user` = ?");
		$query->bindValue(1, $date);
		$query->bindValue(2, $to_user);
		try{
            $query->execute();
			return true;
		}
        catch(PDOException $e) {
            die($e->getMessage());
			return false;
		}
		return false;
	}
}

?>
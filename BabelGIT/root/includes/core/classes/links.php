<?php
class Link {
    
    private $db;
    private $user;
 
    /* constructeur de la classe */
    
	public function __construct($database, $usr) {
	    $this->db = $database;
        $this->user = $usr;
	}
	
	public function add_link($user_id, $title, $link, $usage) {
        $query 	= $this->db->prepare("INSERT INTO `links` (`user_id`, `title`, `url`, `usage`) VALUES (?, ?, ?, ?) ");
        $query->bindValue(1, $user_id);
        $query->bindValue(2, $title);
        $query->bindValue(3, $link);
        $query->bindValue(4, $usage);
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
	
	public function add_admin_link($title, $link, $usage) {
        $query 	= $this->db->prepare("INSERT INTO `admin_links` (`title`, `url`, `usage`) VALUES (?, ?, ?) ");
        $query->bindValue(1, $title);
        $query->bindValue(2, $link);
        $query->bindValue(3, $usage);
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
	
	
	public function update_link($id, $title, $link, $usage) {
        $query 	= $this->db->prepare("UPDATE `links` SET `title`= ?, `url`= ?, `usage`= ? WHERE `id`= ?");
        $query->bindValue(1, $title);
        $query->bindValue(2, $link);
        $query->bindValue(3, $usage);
        $query->bindValue(4, $id);
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

	public function update_admin_link($id, $title, $link, $usage) {
        $query 	= $this->db->prepare("UPDATE `admin_links` SET `title`= ?, `url`= ?, `usage`= ? WHERE `id`= ?");
        $query->bindValue(1, $title);
        $query->bindValue(2, $link);
        $query->bindValue(3, $usage);
        $query->bindValue(4, $id);
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
	
	public function delete_link($id) {
        $query = $this->db->prepare("DELETE FROM `links` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }
	
	public function delete_admin_link($id) {
        $query = $this->db->prepare("DELETE FROM `admin_links` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }
	
	public function get_link($id) {
		$query = $this->db->prepare("SELECT * FROM `links` WHERE `id`=?");
		$query->bindValue(1, $id);
		try{
			$query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
		$arr = $query->fetch();
		$query->closeCursor();
		return $arr;	
	}
	
	public function get_admin_link($id) {
		$query = $this->db->prepare("SELECT * FROM `admin_links` WHERE `id`=?");
		$query->bindValue(1, $id);
		try{
			$query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
		$arr = $query->fetch();
		$query->closeCursor();
		return $arr;	
	}
	
	public function get_all_links($user_id) {
		$query = $this->db->prepare("SELECT * FROM `links` WHERE `user_id`=?");
		$query->bindValue(1, $user_id);
		try{
			$query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
		$arr = $query->fetchAll();
		$query->closeCursor();
		return $arr;
	}
	
	public function get_all_admin_links() {
		$query = $this->db->prepare("SELECT * FROM `admin_links`");
		try{
			$query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
		$arr = $query->fetchAll();
		$query->closeCursor();
		return $arr;
	}
	
	public function aff_all_links($user_id) {
		$links = $this->get_all_links($user_id);
		echo '<table class="link-table">';
		echo '<thead class="link-thead">';
		echo '<tr>';
		echo '<th>Liens Personnels</th>';
		echo '<th>Liens</th>';
		echo '<th>Usages</th>';
		echo '<th>Options</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody class="link-tbody">';
		foreach ($links as $link) {
			echo '<tr>';
			echo '<td>' . $link['title'] . '</td>';
			echo '<td><a href="' . $link['url'] . '">' . $link['url'] . '</a></td>';
			if ($link['usage'] == 'none') {
				echo '<td></td>';
			}
			else {
				echo '<td>' . $link['usage'] . '</td>';
			}
			echo '<td>';
			echo '<i class="edit fa fa-pencil-square" onclick="javascript:editlink(\'' . $link['id']. '\')"></i>';
			echo '<i class="del fa fa-times-circle" onclick="javascript:dellink(\'' . $link['id']. '\')"></i>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	
	public function aff_all_admin_links() {
		$links = $this->get_all_admin_links();
		echo '<table class="link-table admin">';
		echo '<thead class="link-thead">';
		echo '<tr>';
		echo '<th>Liens Administratifs</th>';
		echo '<th>Liens</th>';
		echo '<th>Usages</th>';
		if ((($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
			echo '<th>Options</th>';
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody class="link-tbody">';
		foreach ($links as $link) {
			echo '<tr>';
			echo '<td>' . $link['title'] . '</td>';
			echo '<td><a href="' . $link['url'] . '">' . $link['url'] . '</a></td>';
			if ($link['usage'] == 'none') {
				echo '<td></td>';
			}
			else {
				echo '<td>' . $link['usage'] . '</td>';
			}
			if ((($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
				echo '<td>';
				echo '<i class="edit fa fa-pencil-square" onclick="javascript:editadminlink(\'' . $link['id']. '\')"></i>';
				echo '<i class="del fa fa-times-circle" onclick="javascript:deladminlink(\'' . $link['id']. '\')"></i>';
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
}
?>
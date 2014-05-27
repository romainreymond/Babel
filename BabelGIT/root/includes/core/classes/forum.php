<?php
class Forum {
    
    private $db;
    private $user;
 
    /* constructeur de la classe */
    
	public function __construct($database, $usr) {
	    $this->db = $database;
        $this->user = $usr;
	}
	
	public function add_topic($parent_id, $author_id, $title, $content, $date) {
        $query 	= $this->db->prepare("INSERT INTO `forum_topic` (`parent_id`, `author_id`, `title`, `content`, `date_posted`) VALUES (?, ?, ?, ?, ?) ");
        $query->bindValue(1, $parent_id);
        $query->bindValue(2, $author_id);
        $query->bindValue(3, $title);
        $query->bindValue(4, $content);
        $query->bindValue(5, $date);
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
	
	public function add_sub_block($block_id, $title) {
        $query 	= $this->db->prepare("INSERT INTO `forum_sub_blocks` (`block_id`, `title`) VALUES (?, ?) ");
        $query->bindValue(1, $block_id);
        $query->bindValue(2, $title);
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
	
	public function add_block($title) {
        $query 	= $this->db->prepare("INSERT INTO `forum_blocks` (`title`) VALUES (?)");
        $query->bindValue(1, $title);
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
	
	public function update_topic($id, $title, $content, $date) {
        $query 	= $this->db->prepare("UPDATE `forum_topic` SET `title`= ?, `content`= ?, `date_posted`= ? WHERE `id`= ?");
        $query->bindValue(1, $title);
        $query->bindValue(2, $content);
        $query->bindValue(3, $date);
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
	
	public function update_sub_block($id, $title) {
		$query 	= $this->db->prepare("UPDATE `forum_sub_blocks` SET `title`= ? WHERE `id`= ?");
        $query->bindValue(1, $title);
        $query->bindValue(2, $id);
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
	
	public function update_block($id, $title) {
		$query 	= $this->db->prepare("UPDATE `forum_blocks` SET `title`= ? WHERE `id`= ?");
        $query->bindValue(1, $title);
        $query->bindValue(2, $id);
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
	
    public function delete_topic($id) {
        $query = $this->db->prepare("DELETE FROM `forum_topic` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
		$query = $this->db->prepare("DELETE FROM `forum_comments` WHERE `topic_id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }
	
	public function delete_sub_block($id) {
		$topics = $this->get_topics($id);
		foreach ($topics as $topic) {
			$this->delete_topic($topic['id']);
		}
		$query = $this->db->prepare("DELETE FROM `forum_sub_blocks` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
	}
	
	public function delete_block($id) {
		$sub_blocks = $this->get_sub_blocks($id);
		foreach ($sub_blocks as $sub_block) {
			$topics = $this->get_topics($sub_block['id']);
			foreach ($topics as $topic) {
				$this->delete_topic($topic['id']);
			}
		}
		$query = $this->db->prepare("DELETE FROM `forum_sub_blocks` WHERE `block_id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
		$query = $this->db->prepare("DELETE FROM `forum_blocks` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
	}
	
	public function get_all_blocks() {
		$query = $this->db->prepare("SELECT * FROM `forum_blocks`");
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
	
	public function get_sub_blocks($block_id) {
		$query = $this->db->prepare("SELECT * FROM `forum_sub_blocks` WHERE `block_id`=?");
		$query->bindValue(1, $block_id);
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
	
	public function get_sub_block($sub_block_id) {
		$query = $this->db->prepare("SELECT * FROM `forum_sub_blocks` WHERE `id`=?");
		$query->bindValue(1, $sub_block_id);
		try{
			$query->execute();
			return $query->fetch();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public function get_topics($parent_id) {
		$query = $this->db->prepare("SELECT * FROM `forum_topic` WHERE `parent_id`=?");
		$query->bindValue(1, $parent_id);
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
	
	public function get_topic($id) {
		$query = $this->db->prepare("SELECT * FROM `forum_topic` WHERE `id`=?");
		$query->bindValue(1, $id);
		try{
			$query->execute();
			return $query->fetch();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public function get_block($block_id) {
		$query = $this->db->prepare("SELECT * FROM `forum_blocks` WHERE `id`=?");
		$query->bindValue(1, $block_id);
		try{
			$query->execute();
			return $query->fetch();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
	}
	
	public function aff_sub_blocks($block_id) {
		echo '<div class="sub-blocks">';
		$sub_blocks = $this->get_sub_blocks($block_id);
		foreach ($sub_blocks as $sub_block) {
			echo '<div class="sub-block">';
			echo '<div class="title">';
			echo '<a href="' . DIR_FORUM . $sub_block['id'] . '/">' . $sub_block['title'] . '</a>';
			echo '</div>';
			echo '<div class="nb-post">';
			echo 'Nombre de topic: ' . count($this->get_topics($sub_block['id']));
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
	
	public function aff_all_blocks() {
		$blocks = $this->get_all_blocks();
		foreach ($blocks as $block) {
			echo '<div class="forum-block blue">';
			echo '<div class="block-title">';
			echo '<div class="title">';
			echo $block['title'];
			echo '</div>';
			if ((($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
				echo '<i class="pull-right fa fa-times-circle" style="color: black; cursor: pointer;" onclick="javascript:delblock(\'' . $block['id']. '\')"></i>';
				echo '<i class="pull-right fa fa-pencil-square" style="color: black; cursor: pointer;" onclick="javascript:editblock(\'' . $block['id']. '\')"></i>';
				echo '<i class="pull-right fa fa-plus-square" style="color: black; cursor: pointer;" onclick="javascript:newsubblock(\'' . $block['id']. '\')"></i>';
			}
			echo '</div>';
			$this->aff_sub_blocks($block['id']);
			echo '</div>';
		}
	}
	
	public function aff_topics($parent_id) {
		echo '<div class="sub-blocks">';
		$topics = $this->get_topics($parent_id);
		foreach ($topics as $topic) {
			echo '<div class="sub-block">';
			echo '<div class="title">';
			echo '<a href="' . DIR_FORUM . $parent_id . '/' . $topic['id'] . '">' . $topic['title'] . '</a>';
			echo '</div>';
			echo '<div class="nb-post">';
			echo 'Nombre de commentaires: ' . count($this->get_comments($topic['id']));
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
    
    public function echo_date($date) {
        if (isset($date)) {
			if ($date == date('Y-m-d')) {
				return 'Aujourd\'hui';
			}
            setlocale(LC_ALL, 'fr_FR', 'French', 'French_France.1252');
            return utf8_encode(strftime("%d %B, %Y", strtotime($date)));
        }
        return null;
    }
	
	public function aff_topic($topic) {
		$author_info = $this->user->userdata($topic['author_id']);
		echo '<div class="sub-blocks topic">';
		echo '<div class="author-info">';
		echo '<ul>';
		echo '<li>';
		echo '<i class="fa fa-user"></i>';
		echo ' ' . $author_info['prenom'] . ' ' . $author_info['nom'];
		echo '</li>';
		echo '<li>';
		echo '<i class="fa fa-calendar"></i>';
        echo ' ' . $this->echo_date($topic['date_posted']);
		echo '</li>';
		echo '</ul>';
		echo '</div>';
		echo '<div class="content">';
		echo $topic['content'];
		echo '</div>';
		echo '</div>';
	}
	
	public function delete_com($com_id) {
		$query = $this->db->prepare("DELETE FROM `forum_comments` WHERE `id` = ?");
		$query->bindValue(1, $com_id);
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
	
	public function add_comment($content, $user_id, $post_id) {
		$date = date('Y-m-d');
		$query = $this->db->prepare("INSERT INTO `forum_comments` (`content`, `user_id`, `topic_id`, `comment_date`) VALUES (?, ?, ?, ?) ");
		$query->bindValue(1, $content);
		$query->bindValue(2, $user_id);
		$query->bindValue(3, $post_id);
		$query->bindValue(4, $date);
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
	
	public function get_comments($topic_id) {
		$query = $this->db->prepare("SELECT * FROM `forum_comments` WHERE `topic_id`=? ORDER BY `comment_date`");
		$query->bindValue(1, $topic_id);
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
	
	
	public function aff_comments($topic_id) {
		$comments = $this->get_comments($topic_id);
		$i = 0;
		foreach ($comments as $comment) {
			$author_info = $this->user->userdata($comment['user_id']);
			if ($i == 0) {
				echo '<div id="comments" class="forum-block blue">';
			}
			else {
				echo '<div class="forum-block blue">';
			}
			echo '<div class="block-title">';
			echo '<div class="title">';
			echo $author_info['nom'];
			echo '</div>';
			if (($comment['user_id'] == $_SESSION['id']) || (($_SESSION['admin'] == 1) && ($_SESSION['admin_connect'] == 1))) {
				echo '<i class="del pull-right fa fa-times-circle" onclick="javascript:delcom(\'' . $comment['id']. '\')"></i>';
			}
			echo '</div>';
			echo '<div class="sub-blocks topic">';
			echo '<div class="author-info">';
			echo '<ul>';
			echo '<li>';
			echo '<i class="fa fa-user"></i>';
			echo ' ' . $author_info['prenom'] . ' ' . $author_info['nom'];
			echo '</li>';
			echo '<li>';
			echo '<i class="fa fa-calendar"></i>';
			echo ' ' . $this->echo_date($comment['comment_date']);
			echo '</li>';
			echo '</ul>';
			echo '</div>';
			echo '<div class="content">';
			echo $comment['content'];
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
}
?>
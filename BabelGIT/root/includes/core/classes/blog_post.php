<?php
class Posts {
    
    private $db;
    private $user;
    private $general;
 
    /* constructeur de la classe */
    
	public function __construct($database, $usr, $gen) {
	    $this->db = $database;
        $this->user = $usr;
		$this->general = $gen;
	}

	/* Retourne un tableau contenant les informations pour la creation de la liste d'archive du blog */
	
	public function get_date_arr() {
		$query = $this->db->prepare("SELECT YEAR(date_posted) as `year`, MONTH(date_posted) as `month` FROM `blog_posts` ORDER BY `date_posted` DESC");
		try{
            $query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
		$row = $query->fetchAll();
		$query->closeCursor();
		return $row;
	}
	
	public function get_posts_by_month($year, $month) {
		$query = $this->db->prepare("SELECT * FROM `blog_posts` WHERE YEAR(date_posted) = ? AND MONTH(date_posted) = ? ORDER BY `date_posted` DESC");
		$query->bindValue(1, $year);
		$query->bindValue(2, $month);
		try{
            $query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
        }
		$row = $query->fetchAll();
		$query->closeCursor();
		return $row;
	}
	
	public function get_posts_by_year($year) {
		$query = $this->db->prepare("SELECT * FROM `blog_posts` WHERE YEAR(date_posted) = ? ORDER BY `date_posted` DESC");
		$query->bindValue(1, $year);
		try{
            $query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
        }
		$row = $query->fetchAll();
		$query->closeCursor();
		return $row;
	}
	
    /* Reformate la date recuperer dans la bdd */
    
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
    
    /* Recupert les données d'un post à partir d'un id et les stock dans un tableau. */
    
	public function get_data($id) {
		$query = $this->db->prepare("SELECT * FROM `blog_posts` WHERE `id`= ?");
		$query->bindValue(1, $id);
		try{
            $query->execute();
            return $query->fetch();
		}
		catch(PDOException $e) {
			die($e->getMessage());
        }
	}

	/* Recupert les données d'un post à partir d'une url et les stock dans un tableau. */
	
    public function postdata($urls) {
		$exp = explode("/", $urls);
		$url = end($exp);
		$query = $this->db->prepare("SELECT * FROM `blog_posts` WHERE `url`= ?");
		$query->bindValue(1, $url);
		try{
            $query->execute();
            return $query->fetch();
		}
		catch(PDOException $e) {
			die($e->getMessage());
        }
    }
	
	/*
	
	Vérifie l'existance d'un url dans la BDD.
	
	*/
	
    public function check_exist($url, $id) {
        if (isset($id)) {
            $query = $this->db->prepare("SELECT COUNT(`id`) FROM `blog_posts` WHERE `url`= ? AND `id`!= ?");
            $query->bindValue(1, $url);
            $query->bindValue(2, $id);
        }
        else {
            $query = $this->db->prepare("SELECT COUNT(`id`) FROM `blog_posts` WHERE `url`= ?");
            $query->bindValue(1, $url);
        }
        try{
            $query->execute();
            $rows = $query->fetchColumn();
            if($rows == 1) {
                return true;
            }
            else {
                return false;
            }
     
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
	/*
	
	Affiche les tags associés à un post.
	
	*/
	
	public function get_tags($post_id) {
		$query = $this->db->prepare("SELECT `tag_id` FROM `blog_post_tag` WHERE `post_id`= ?");
		$query->bindValue(1, $post_id);
        try{
            $query->execute();
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
		$all_tag_id = $query->fetchAll();
		foreach ($all_tag_id as $tag_id) {
			$query = $this->db->prepare("SELECT `name` FROM `tags` WHERE `id`= ?");
			$query->bindValue(1, $tag_id['tag_id']);
			try{
				$query->execute();
				$row = $query->fetch();
				$query->closeCursor();
				echo '<a href="' . DIR_BLOG . 'C-' . $row['name'] . '">' . $row['name'] . '</a> ';
			}
			catch (PDOException $e) {
				die($e->getMessage());
			}
		}
	}
	
	/*
	
	Retourne le nombre de commentaire pour un id de post passer en commentaire.
	
	*/
	public function nb_comments($post_id) {
		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `comments` WHERE `post_id`=?");
		$query->bindValue(1, $post_id);
        try{
            $query->execute();
			return $query->fetchColumn();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }		
	}
	
	/*
	
	Affiche un post avec image sur la page de blog.
	
	*/
	
    public function aff_post_img($row) {
        $usrdata = $this->user->userdata($row['author_id']);
        $post_url = DIR_BLOG . str_replace("-", "/", $row['date_posted']) . '/' . $row['url'];
        echo '<hr>';
        echo '<div class="row">';
        echo '<div class="col-md-4 blog-tag-data">';
		echo '<a href="' . $post_url . '">';
		echo '<div class="frame">';
        echo '<img src="' . $row['img_url'] . '" />';
		echo '</div>';
		echo '</a>';
        echo '<ul class="list-inline">';
        echo '<li>';
        echo '<i class="fa fa-user"></i><a href="#"> ' . utf8_decode($usrdata['prenom']) . ' ' . utf8_decode($usrdata['nom']) . '</a>';
        echo '</li>';
        echo '</ul>';
        echo '<ul class="list-inline">';
        echo '<li>';
        echo '<i class="fa fa-calendar"></i>';
        echo '<a href="'. DIR_BLOG . str_replace("-", "/", $row['date_posted']) .'/"> ' . $this->echo_date($row['date_posted']) . '</a>';
        echo '</li>';
		echo '</ul>';
        echo '<ul class="list-inline">';
        echo '<li>';
        echo '<i class="fa fa-comments"></i>';
        echo '<a href="' . $post_url . '#comments"> ' . $this->nb_comments($row['id']) . ' Commentaires</a>';
        echo '</li>';
        echo '</ul>';
        echo '<ul class="list-inline blog-tags">';
        echo '<li>';
        echo '<i class="fa fa-tags"></i> ';
        $this->get_tags($row['id']);
        echo '</li>';
        echo '</ul>';
        echo '</div>';
        echo '<div class="col-md-8 blog-article">';
        echo '<h3><a href="' . $post_url . '">' . $row['title'] . '</a></h3>';
		if ($row['desc'] == 'none') {
			echo '<div class="truncate no-img">';
        	echo $row['content'];
			echo '</div>';
		}
		else {
			echo '<div><p>';
			echo $row['desc'];
			echo '</p></div>';
		}
        echo '<a class="btn blue" style="margin-top: 20px;text-decoration: none;" href="'. $post_url .'">Lire la suite <i class="fa fa-arrow-circle-o-right-"></i></a>';
        echo '</div>';
        echo '</div>';
    }
    
	/*
	
	Affiche un post sans image sur la page de blog.
	
	*/
	
    public function aff_post_noimg($row) {
        $usrdata = $this->user->userdata($row['author_id']);
        $post_url = DIR_BLOG . str_replace("-", "/", $row['date_posted']) . '/' . $row['url'];
        echo '<hr>';
        echo '<div class="row">';
        echo '<div class="col-md-12 blog-article blog-tag-data">';
        echo '<h3><a href="' . $post_url . '">' . $row['title'] . '</a></h3>';
		if ($row['desc'] == 'none') {
			echo '<div class="truncate no-img">';
        	echo $row['content'];
			echo '</div>';
		}
		else {
			echo '<div><p>';
			echo $row['desc'];
			echo '</p></div>';
		}
        echo '<ul class="list-inline">';
        echo '<li>';
        echo '<i class="fa fa-user"></i><a href="#"> ' . utf8_decode($usrdata['prenom']) . ' ' . utf8_decode($usrdata['nom']) . '</a>';
        echo '</li>';
        echo '<li>';
        echo '<i class="fa fa-calendar"></i>';
        echo '<a href="'. DIR_BLOG . str_replace("-", "/", $row['date_posted']) .'/"> ' . $this->echo_date($row['date_posted']) . '</a>';
        echo '</li>';
        echo '<li>';
        echo '<i class="fa fa-comments"></i>';
        echo '<a href="' . $post_url . '#comments"> ' . $this->nb_comments($row['id']) . ' Commentaires</a>';
        echo '</li>';
        echo '</ul>';
        echo '<ul class="list-inline blog-tags">';
        echo '<li>';
        echo '<i class="fa fa-tags"></i> ';
        $this->get_tags($row['id']);
        echo '</li>';
        echo '</ul>';
        echo '<a class="btn blue pull-right" href="'. $post_url .'">Lire la suite <i class="fa fa-arrow-circle-o-right-"></i></a>';   
        echo '</div>';
        echo '</div>';
    }
    
	/*
	
	Verifie l'existance d'un tag dans la base de donner et retourne son id si il existe.
	
	*/
	
	public function tag_exist($name) {
		$query = $this->db->prepare("SELECT `id` FROM `tags` WHERE `name`= ?");
		$query->bindValue(1, $name);
        try{
            $query->execute();
            $row =  $query->fetch();
			return $row['id'];
        }
        catch (PDOException $e) {
            return null;
        }
    }
	
	/*
	
	Link un tag et post.
	
	*/
	
	public function insert_tag($tag_id, $post_id) {
		$query = $this->db->prepare("INSERT INTO `blog_post_tag` (`tag_id`, `post_id`) VALUES (?, ?) ");
		$query->bindValue(1, $tag_id);
		$query->bindValue(2, $post_id);
		try{
			$query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
	}
	
	/*
	
	Recupert l'id d'un post grace a l'url.
	
	*/
	
	public function get_id($url) {
		$query = $this->db->prepare("SELECT `id` FROM `blog_posts` WHERE `url`= ?");
		$query->bindValue(1, $url);
        try{
            $query->execute();
            $row = $query->fetch();
     		return $row['id'];
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
	}
	
	/*
	
	Recupert tous les tags de la BDD et les formatent pour etre utiliser par une appli java.
	
	*/
	
	public function get_all_tags() {
		$query = $this->db->prepare("SELECT * FROM `tags`");
		try{
			$query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
		return $query->fetchAll();
	}
	
	public function aff_tags() {
		$tags = $this->get_all_tags();
		$ret = '';
		$i = 0;
		foreach ($tags as $tag) {
			if ($i != 0) {
				$ret .= ', ';
			}
			$ret .= '"' . $tag['name'] . '"';
			$i++;
		}
		return $ret;
	}
	
	/*
		Ajoute des tags dans la base de donner et les links à un post.
	*/
	
	public function add_tag($tags, $post_id, $url) {
		$tags = explode(",", $tags);
		foreach ($tags as $tag) {
			if ($tag != "Tags") {
				$tag = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($tag))));
				$tag_id = $this->tag_exist($tag);
				if (isset($url)) {
					$post_id = $this->get_id($url);
				}
				if (isset($post_id) && isset($tag_id)) {
					$this->insert_tag($tag_id, $post_id);
				}
				else if (isset($post_id)){
					$query = $this->db->prepare("INSERT INTO `tags` (`name`) VALUES (?) ");
					$query->bindValue(1, $tag);
					try{
						$query->execute();
					}
					catch(PDOException $e) {
						die($e->getMessage());
					}
					$tag_id = $this->tag_exist($tag);
					$this->insert_tag($tag_id, $post_id);
				}
			}
		}
	}
	
    /* 
        Permet de créer une nouvelle ligne dans la base de donnée du blog pour un nouveau post
        et d'y mettre les infos que l'utilisateur a inséré.
    */
	
    public function add_post($title, $url, $desc, $content, $author_id, $date, $img_url, $tags) {
        $n = 2;
        while ($this->check_exist($url, null)) {
            if ($n == 2) {
                $url .= '-' . $n;
            }
            else {
                $url = substr($url, 0, -1) . $n;
            }
            $n++;
        }    
        $query 	= $this->db->prepare("INSERT INTO `blog_posts` (`title`, `url`, `desc`, `content`, `author_id`, `date_posted`, `img_url`) VALUES (?, ?, ?, ?, ?, ?, ?) ");
        $query->bindValue(1, $title);
        $query->bindValue(2, $url);
        $query->bindValue(3, $desc);
        $query->bindValue(4, $content);
        $query->bindValue(5, $author_id);
        $query->bindValue(6, $date);
        $query->bindValue(7, $img_url);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
		$this->add_tag($tags, null, $url);
		return true;
    }

	/*
		Utilisé pour eviter les doublons de liens quand une update d'un post est lancé.
	*/
	
	public function update_tags($tags, $post_id) {
		$query = $this->db->prepare("DELETE FROM `blog_post_tag` WHERE `post_id` = ?");
        $query->bindValue(1, $post_id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
		$this->add_tag($tags, $post_id, null);
	}
    
	/*
		Prepâre pour le java les tags.
	*/
	
	public function aff_post_tags($post_id) {
		$query = $this->db->prepare("SELECT `tag_id` FROM `blog_post_tag` WHERE `post_id`= ?");
		$query->bindValue(1, $post_id);
        try{
            $query->execute();
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
		$all_tag_id = $query->fetchAll();
		$ret = '[';
		$i = 0;
		foreach ($all_tag_id as $tag_id) {
			if ($i != 0) {
				$ret .= ', ';
			}
			$query = $this->db->prepare("SELECT `name` FROM `tags` WHERE `id`= ?");
			$query->bindValue(1, $tag_id['tag_id']);
			try{
				$query->execute();
				$row = $query->fetch();
				$query->closeCursor();
				$ret .= '"' . $row['name'] . '"';
				$i++;
			}
			catch (PDOException $e) {
				die($e->getMessage());
			}
		}
		$ret .= ']';
		if ($i == 0) {
			return '""';
		}
		return $ret;
	}
	
	/*
		Fonction permettant l'update d'un post.
	*/
	
    public function update_post($id, $title, $url, $desc, $content, $date, $img_url) {
        $n = 2;
        while ($this->check_exist($url, $id)) {
            if ($n == 2) {
                $url .= '-' . $n;
            }
            else {
                $url = substr($url, 0, -1) . $n;
            }
            $n++;
        }
        $query 	= $this->db->prepare("UPDATE `blog_posts` SET `title`= ?, `url`= ?, `desc`= ?, `content`= ?, `date_posted`= ?, `img_url`= ? WHERE `id`= ?");
        $query->bindValue(1, $title);
        $query->bindValue(2, $url);
        $query->bindValue(3, $desc);
        $query->bindValue(4, $content);
        $query->bindValue(5, $date);
        $query->bindValue(6, $img_url);
        $query->bindValue(7, $id);
        try{
            $query->execute();
            return $url;
        }
        catch(PDOException $e) {
            die($e->getMessage());
            return null;
        }
        return null;
    }
    
	/*
		Fonction permettant la suppression d'un post grace à son id.
	*/
	
    public function delpost($id) {
        $query = $this->db->prepare("DELETE FROM `blog_posts` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
		$query = $this->db->prepare("DELETE FROM `blog_post_tag` WHERE `post_id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
		$query = $this->db->prepare("DELETE FROM `comments` WHERE `post_id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }
    
    /*
		Recupert toutes les données contenu dans la table 'blog_posts' et les stock dans un tableau 
	*/
    
    public function get_posts() {
        $query = $this->db->prepare("SELECT * FROM `blog_posts` ORDER BY `date_posted` DESC ,`id`  DESC");
        try{
            $query->execute();
        }
        catch(PDOException $e) { 
            die($e->getMessage());
      	}
        // On utilise fetchAll() a la place de fetch() afin de recuperer un tableau de toutes les données.
        return $query->fetchAll();
    }
	
	/*
		Recupert toutes les données contenu dans la table 'blog_posts' limiter par la variable max et les stock dans un tableau 
	*/
    
    public function get_posts_max($max) {	
        $query = $this->db->prepare("SELECT * FROM `blog_posts` ORDER BY `date_posted` DESC ,`id`  DESC $max");
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }     
        // On utilise fetchAll() a la place de fetch() afin de recuperer un tableau de toutes les données.
        return $query->fetchAll();
    }
	
	/*
		Recupert tous les posts d'une categorie ou d'un tag donné.
	*/
	
	public function get_posts_cat($tag_name) {
		$tag_id = $this->tag_exist($tag_name);
		if (!isset($tag_id)) {
			header('Location: ' . DIR_BLOG);
		}
		$query = $this->db->prepare("SELECT `post_id` FROM `blog_post_tag` WHERE `tag_id`=? ORDER BY `post_id` DESC");
        $query->bindValue(1, $tag_id);
        try{
            $query->execute();
			$res = $query->fetchAll();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
		$arr = array();
		$i = 0;
		foreach($res as $row) {
			$new = $this->get_data($row['post_id']);
			$arr[$i] = $new;
			$i++;
		}
		return $arr;
	}
	
	/*
		Recupert tous les posts à une date donnée.
	*/
	
	public function get_posts_date($date) {
		$query = $this->db->prepare("SELECT * FROM `blog_posts` WHERE `date_posted`= ? ORDER BY `id` DESC, `date_posted` DESC");
        $query->bindValue(1, $date);
        try{
            $query->execute();
			return $query->fetchAll();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
	}
	/*
		Recupert les commentaires d'un post.
	*/
	
	public function get_comments($post_id) {
		$query = $this->db->prepare("SELECT * FROM `comments` WHERE `post_id`=? ORDER BY `comment_date`");
		$query->bindValue(1, $post_id);
        try{
            $query->execute();
			return $query->fetchAll();
        }
        catch(PDOException $e) {
            die($e->getMessage());
		}
	}
	
	/*
		Ajoute dans la BDD un commentaire associé à un post.
	*/
	
	public function add_comment($content, $user_id, $post_id) {
		$date = date('Y-m-d');
		$query = $this->db->prepare("INSERT INTO `comments` (`content`, `user_id`, `post_id`, `comment_date`) VALUES (?, ?, ?, ?) ");
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
		}
	}
	
	/*
		Supprime de la BDD un commentaire.
	*/
	
	public function del_com($com_id, $post_id) {
		$query = $this->db->prepare("SELECT * FROM `comments` WHERE `id`=?");
		$query->bindValue(1, $com_id);
        try{
            $query->execute();
			$row = $query->fetch();
			$query->closeCursor();
        }
        catch(PDOException $e) {
            die($e->getMessage());
		}
		$this->general->del_notif_by_ref($post_id, $row['user_id'], $row['comment_date']);
		$query = $this->db->prepare("DELETE FROM `comments` WHERE `id` = ?");
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
	
	/*
		Retourne le nombre de post du blog.
	*/
	
	public function get_nb_post() {
		$nrows = $this->get_posts();
		return count($nrows);
	}
	
	
	/*
		Formate le titre d'un post pour etre utilisé en tant qu'url.
	*/
	
	public function slug($text) { 
      // remplace les caractères spéciaux par -
      $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
      // trim
      $text = trim($text, '-');
      // transliterate
      //$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      // lowercase
      $text = strtolower($text);
      // retire les caractère que l'on ne veut pas.
      $text = preg_replace('~[^-\w]+~', '', $text);
      if (empty($text))
      {
        return 'n-a';
      }
      return $text;
    }
	
	public function nb_post_with_tag($tag_id) {
		$query = $this->db->prepare("SELECT `post_id` FROM `blog_post_tag` WHERE `tag_id`=?");
		$query->bindValue(1, $tag_id);
		try{
			$query->execute();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
		$nb = $query->fetchAll();
		$query->closeCursor();
		return count($nb);
	}
	
	public function aff_html_cat($name, $nb_post, $i) {
		if ($i == 0) {
			$color = 'red';
		}
		if ($i == 1) {
			$color = 'green';
		}
		if ($i == 2) {
			$color = 'blue';
		}
		if ($i == 3) {
			$color = 'yellow';
		}
		if ($i == 4) {
			$color = 'purple';
		}
		echo '<a href="' . DIR_BLOG . 'C-' . $name . '" class="btn ' . $color . '">';
		echo '<span>' . $name . '</span>';
		echo '<em>Nombre de post : ' . $nb_post . '</em>';
		echo '<i class="fa fa-tags top-tags-icon"></i>';
		echo '</a>';
	}
	
	public function aff_categories() {
		$tags = $this->get_all_tags();
		$array = array();
		foreach($tags as $tag) {
			$nb_post = $this->nb_post_with_tag($tag['id']);
			$array[$tag['name']] = $nb_post;
		}
		arsort($array);
		$i = 0;
		foreach ($array as $name => $nb_post) {
			if ($i == 5) {
				unset($array);
				return ;
			}
			$this->aff_html_cat($name, $nb_post, $i);
			$i++;
		}
	}
	
	public function get_news_tags($post_id) {
		$query = $this->db->prepare("SELECT `tag_id` FROM `blog_post_tag` WHERE `post_id`= ?");
		$query->bindValue(1, $post_id);
        try{
            $query->execute();
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
		$all_tag_id = $query->fetchAll();
		$i = 0;
		foreach ($all_tag_id as $tag_id) {
			$query = $this->db->prepare("SELECT `name` FROM `tags` WHERE `id`= ?");
			$query->bindValue(1, $tag_id['tag_id']);
			try{
				$query->execute();
				$row = $query->fetch();
				$query->closeCursor();
				if ($i != 0) {
					echo ', ';
				}
				echo $row['name'];
				$i++;
			}
			catch (PDOException $e) {
				die($e->getMessage());
			}
		}
	}
	
	public function aff_news($row) {
		$usrdata = $this->user->userdata($row['author_id']);
		$post_url = DIR_BLOG . str_replace("-", "/", $row['date_posted']) . '/' . $row['url'];
		if ($row['date_posted'] == date('Y-m-d')) {
			echo '<div class="news-blocks today">';
		}
		else {
			echo '<div class="news-blocks">';
		}
		echo '<h3><a href="' . $post_url . '">' . $row['title'] . '</a></h3>';
		if ($row['desc'] == 'none') {
			echo '<div class="truncate100 no-img">';
        	echo $row['content'];
			echo '</div>';
		}
		else {
			echo '<div><p>';
			echo $row['desc'];
			echo '</p></div>';
		}
		echo '<div class="news-block-tags">';
		echo '<strong>';
		$this->get_news_tags($row['id']);
		echo ' - ' . $this->echo_date($row['date_posted']) . ' - ' . $usrdata['prenom'] . ' ' . $usrdata['nom'] . '</strong>';
		echo '</div>';
        echo '<a class="news-block-btn" href="'. $post_url .'">Lire la suite <i class="fa fa-arrow-circle-o-right"></i></a>';
		echo '</div>';
	}
	
	public function aff_new_post() {
		$arr = $this->get_posts();
		if (isset($arr)) {
			$i = 1;
			foreach ($arr as $row) {
				if ($i == 7) {
					return ;
				}
				if ($i == 1) {
					echo '<div class="col-md-5">';
				}
				if ($i == 3) {
					echo '<div class="col-md-4">';
				}
				if ($i == 5) {
					echo '<div class="col-md-3">';
				}
				$this->aff_news($row);
				if ($i == 2 || $i == 4 || $i == 6) {
					echo '</div>';
				}
				$i++;
			}
		}
	}
}
?>

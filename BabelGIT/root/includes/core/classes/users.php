<?php 
class Users{
 
	private $db;
 
    /* constructeur de la classe */
    
	public function __construct($database) {
	    $this->db = $database;
	}

    /* Fonction de verification de l'existance d'un username dans la base de donnée */
    
    public function user_exists($username) {
        $query = $this->db->prepare("SELECT COUNT(`id`) FROM `people` WHERE `username`= ?");
        $query->bindValue(1, $username);
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
 
    /* Fonction de verification de l'existance d'un email dans la base de donnée */
    
    public function email_exists($email) {
        $query = $this->db->prepare("SELECT COUNT(`id`) FROM `people` WHERE `email`= ?");
        $query->bindValue(1, $email);
        try{
            $query->execute();
            $rows = $query->fetchColumn();
            if($rows == 1){
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
     
    /* La fonction register qui permet d'enregistrer un nouvel utilisateur sur la base de donnée */
    
    public function register($username, $nom, $prenom, $password, $email){
        global $bcrypt;
        $password   = $bcrypt->genHash($password);
        $query 	= $this->db->prepare("INSERT INTO `people` (`username`, `password`, `email`, `nom`, `prenom`) VALUES (?, ?, ?, ?, ?) ");
        $query->bindValue(1, $username);
        $query->bindValue(2, $password);
        $query->bindValue(3, $email);
        $query->bindValue(4, $nom);
        $query->bindValue(5, $prenom);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    
    /* Ajoute un token en cookie et dans la base de donnée si l'utilisateur a cocher la case de login automatique */
    
    public function onlogin($id) {
        $token = uniqid(mt_rand(), true);
        $time = time()+3600*24*30;
        $query 	= $this->db->prepare("UPDATE `people` SET `token`=? WHERE `id`=?");
        $query->bindValue(1, $token);
        $query->bindValue(2, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
        $cookie = $id . ':' . $token;
        $mac = crypt($cookie);
        $cookie .= ':' . $mac;
        setcookie('auth', $cookie, $time, '/');
    }
    
    /* Check si l'utilisateur à le cookie rememberme qui permet de se connecter sans remettre ses identifiant et verifie la validité des données du cookie */
    
    public function rememberme() {
        if (isset($_COOKIE['auth'])) {
            $cookie = $_COOKIE['auth'];
            list ($id, $token, $mac) = explode(':', $cookie);
            if ($mac !== crypt($id . ':' . $token, $mac)) {
                return null;
            }
            $query = $this->db->prepare("SELECT `token`, `nom` FROM `people` WHERE `id` = ?");
            $query->bindValue(1, $id);
            try {
                $query->execute();
                $data = $query->fetch();
                if ($data['token'] !== $token) {
                    return null;
                }
                return $id;
            }
            catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return null;
    }
    
    /* Fonction de login verifiant les données entré par l'utilisateur lors de la page de connection. */
    
    public function login($username, $password) {
        global $bcrypt;
        $query = $this->db->prepare("SELECT `password`, `id` FROM `people` WHERE `username` = ?");
        $query->bindValue(1, $username);
        try{
            $query->execute();
            $data 				= $query->fetch();
            $stored_password 	= $data['password'];
            $id 				= $data['id'];
            #using the verify method to compare the password with the stored hashed password.
            if($bcrypt->verify($password, $stored_password) === true){
                return $id;
            }
            else {
                return false;	
            }
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }
    
    /* Recupert les données d'un utilisateur et les stock dans un tableau. */
    
    public function userdata($id) {
        $query = $this->db->prepare("SELECT * FROM `people` WHERE `id`= ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
            return $query->fetch();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }
    
    /* Recupert tout les utilisateurs et renvoie toute leurs infos */
    
    public function get_users() {
        #preparing a statement that will select all the registered users, with the most recent ones first.
        $query = $this->db->prepare("SELECT * FROM `people` ORDER BY `id` DESC");        
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }     
        # We use fetchAll() instead of fetch() to get an array of all the selected records.
        return $query->fetchAll();
    }
    
	/*
		Supprime un utilisateur de la BDD.
	*/
	
    public function delete_user($id) {
        $query = $this->db->prepare("DELETE FROM `people` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
    }
	
	public function is_admin($id) {
		$query = $this->db->prepare("SELECT `admin` FROM `people` WHERE `id` = ?");
        $query->bindValue(1, $id);
        try{
            $query->execute();
			$row = $query->fetch();
			return $row['admin'];
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
	}
}

?>

<?php
$bc=explode("/",$_SERVER["PHP_SELF"]);
echo '<li>
        <i class="fa fa-home"></i>
        <a href="'.VIEW_IND.'">Accueil</a> 
        <i class="fa fa-angle-right"></i>
    </li>';
while(list($key,$val)=each($bc)){
    $dir='/'.$bc[1];
    if($key > 0){
        $n=2;
        while($n <= $key){
            $dir.='/'.$bc[$n];
            $val=$bc[$n];
            $n++;
        }
        if($key < count($bc) - 1) {
            echo '<li><a href="' . $dir.'">'.$val.'</a><i class="fa fa-angle-right"></i></li>';
        }
    }
}
echo '<li><a style="text-decoration: none !important;">' . $pagetitle . '</a></i></li>';
?>

<?php 
require('../config.php');
$_GET['page'] = "settings";
include(VIEW_HEADER);
?>
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title">Paramètres <small>Configurez votre portail</small></h3>
                        <ul class="page-breadcrumb breadcrumb">
                            <li class="btn-group">
                                <?php 
                                $pagetitle = "Paramètres";
                                include(INC_BDCB);
                                ?>
                        </ul>
                    </div>
                </div>
                <div class="blog-page">
                    <h1>Page en construction</h1>
                    <a class="btn red" href="javascript:deluser()">Supprimer mon compte</a>
                </div>
            </div>
        </div>
<script language="JavaScript" type="text/javascript">
    function deluser() {
        if (confirm("Etes-vous sûr de vouloir supprimer votre compte ?")) {
            window.location.href = '<?php echo HTTP_BASE; ?>logout.php?deluser=<?php echo $_SESSION['id']; ?>';
        }
    }
</script>
<?php include(VIEW_FOOTER); ?>
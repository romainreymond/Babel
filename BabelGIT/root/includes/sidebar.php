            <div class="nav">
                <ul class="page-sidebar-menu">
                    <li>
                        <div class="sidebar-toggler"></div>
                    </li>
                    <li>
                       <form class="sidebar-search" action="javascript:;" method="POST">
                          <div class="form-container">
                              <div class="input-box">
                                  <a href="javascript:;" class="remove"></a>
                                  <input id="search_input" type="text" placeholder="Recherche..."/>
                                  <input type="button" class="submit" value=" "/>
                             </div>
                          </div>
                       </form>
                    </li>
                    <?php if (isset($_GET['page']) && $_GET['page'] == "home") { ?>
                    <li class="start active">
                    <?php } else { ?>
                    <li class="start">
                    <?php } ?>
                        <a href="<?php print HTTP_BASE; ?>">
                            <i class="fa fa-home"></i>
                            <span class="title"> Accueil</span>
                            <?php if (isset($_GET['page']) && $_GET['page'] == "home") { ?>
                            <span class="selected"></span>
                            <?php } ?>
                        </a>
                    </li>
                    <?php if (isset($_GET['page']) && $_GET['page'] == "blog") { ?>
                    <li class="active">
                    <?php } else { ?>
                    <li>
                    <?php } ?>
                        <a href="<?php print DIR_BLOG; ?>blog_page.php">
                            <i class="fa fa-comments"></i>
                            <span class="title"> Blog</span>
                            <?php if (isset($_GET['page']) && $_GET['page'] == "blog") { ?>
                            <span class="selected"></span>
                            <?php } ?>
                        </a>
                    </li>
                    <?php if (isset($_GET['page']) && $_GET['page'] == "forum") { ?>
                    <li class="active">
                    <?php } else { ?>
                    <li>
                    <?php } ?>
                        <a href="<?php print DIR_FORUM; ?>forum.php">
                            <i class="fa fa-users"></i>
                            <span class="title"> Forum</span>
                            <?php if (isset($_GET['page']) && $_GET['page'] == "forum") { ?>
                            <span class="selected"></span>
                            <?php } ?>
                        </a>
                    </li>
                    <?php if (isset($_GET['page']) && $_GET['page'] == "links") { ?>
                    <li class="active">
                    <?php } else { ?>
                    <li>
                    <?php } ?>
                        <a href="<?php print DIR_LINK; ?>links.php">
                            <i class="fa fa-external-link"></i>
                            <span class="title"> Liens</span>
                            <?php if (isset($_GET['page']) && $_GET['page'] == "links") { ?>
                            <span class="selected"></span>
                            <?php } ?>
                        </a>
                    </li>
                    <?php if (isset($_GET['page']) && $_GET['page'] == "settings") { ?>
                    <li class="active">
                    <?php } else { ?>
                    <li>
                    <?php } ?>
                        <a href="<?php print DIR_SET; ?>settings.php">
                            <i class="fa fa-cogs"></i>
                            <span class="title"> Paramètres</span>
                            <?php if (isset($_GET['page']) && $_GET['page'] == "settings") { ?>
                            <span class="selected"></span>
                            <?php } ?>
                        </a>
                    </li>
					<?php 
					if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
						if ($_SESSION['admin_connect'] == 1) {
							echo '<li class="active">';
							echo '<a href="' . HTTP_BASE . 'index.php?admin=0&url=' . $_SERVER['REQUEST_URI'] . '">';
						}
						else {
							echo '<li>';
							echo '<a href="' . HTTP_BASE . 'index.php?admin=1&url=' . $_SERVER['REQUEST_URI'] . '">';
						}
						echo '<i class="fa fa-user"></i>';
						echo '<span class="title"> Admin</span>';
						echo '</a>';
						echo '</li>';					
					}
					?>
                    <li>
                        <a href="<?php print HTTP_BASE; ?>logout.php">
                            <i class="fa fa-sign-out"></i>
                            <span class="title"> Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>

<?php
	/* Setup everything, import configuration and connect to database
	 */
	include_once('include/functions.php');
	include_once('config/config.php');

	session_name('HelthApp');
	session_start();
	setLanguage($CONFIG['default_language']);
	$_SESSION['theme'] = $CONFIG['default_theme'];
	$_SESSION['role'] = isset($_SESSION['role']) ? $_SESSION['role'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Health application</title>

		<!-- Import CCS styles -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/styles.css">
 		<link rel="stylesheet" href="assets/css/color_<?php echo $_SESSION['theme']; ?>.css">

 		<!-- Import JS frameworks -->
 		<script src="assets/js/jquery-3.6.0.min.js"></script>
		<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="assets/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/js/chart.min.js"></script>

		<!-- Enable collapse sidebar -->
		<script type="text/javascript">
			$(document).ready(function () {
				$("#sidebar").mCustomScrollbar({
					theme: "minimal"
				});

				$('#sidebarCollapse').on('click', function () {
					$('#sidebar, #content').toggleClass('active');
					$('.collapse.in').toggleClass('in');
					$('a[aria-expanded=true]').attr('aria-expanded', 'false');
				});
			});
		</script>
</head>

<body>
	<?php
		/* Array to build the side bar menu. Support section title, menu items and sub menus
		 * Main page is fetch from pages/<pagename>.php. Session: 0 = guests and 1+ defined in database roles
		 *   array('title' => 'Menu text', 'page' => '<pagename>', 'role' => [0,1,2,3...])
		 *
		 * Insert a section title in the menu. Note the '_head' tag in page.
		 *   array('title' => 'Text', 'page' => '_head'),
		 *
		 * Build a collapsible menu.
		 *   array('title' => 'Sub menu title', 'page' => 'submenu ID', 'role' => [0,1,2,3...], 'submenu' => array(
		 *     array('title' => 'Sub menu text', 'page' => '<pagename>'), 'role' => [0,1,2,3...] ... )
		 */
		$menuStructure = array(
			array('title' => _('Dashboard'), 'page' => 'home', 'role' => [0,1,2]),
			array('title' => _('Register'), 'page' => 'register', 'role' => [0]),
			array('title' => _('Login'), 'page' => 'login', 'role' => [0]),
			array('title' => _('Logout'), 'page' => 'logout', 'role' => [1,2]),
 			array('title' => _('About'), 'page' => 'about', 'role' => [0,1,2]),
			array('title' => _('Account'), 'page' => '_head', 'role' => [0,1,2]),
			array('title' => _('My settings'), 'page' => 'usersettings', 'role' => [1,2]),
			array('title' => _('My account'), 'page' => 'useraccount', 'role' => [1,2]),
 			array('title' => _('Admin'), 'page' => '_head', 'role' => [1]),
			array('title' => _('User admin'), 'page' => 'useradmin', 'role' => [1], 'submenu' =>
				array(
					array('title' => _('Edit users'), 'page' => 'edituser', 'role' => [1]),
					array('title' => _('Add user'), 'page' => 'adduser', 'role' => [1])
				),
			),
			array('title' => _('Settings'), 'page' => 'settings', 'role' => [1,2]),
		);
	?>
	<div class="wrapper">
		<!-- Build sidebar from $menuStructure array -->
		<nav id="sidebar">
			<div class="sidebar-header">
				<div class="face-image"></div>
				<p class="face-text"><?php echo _("Logged in as")?> Michael</p>
			</div>
			<ul class="list-unstyled"> <?php
				$page = verifyData(isset($_GET['page'])?$_GET['page']:'home',"page", false);
				foreach ($menuStructure as $item) {
					if (in_array($_SESSION['role'], $item['role'])) {
						if (!isset($item['submenu'])) {
							if ($item['page'] == '_head') {
								echo '<p>'.$item['title'].'</p>';
							} else {
								echo '<li class="'.($item['page']==$page?'active':'').'"><a href="?page='.$item['page'].'">'.$item['title'].'</a></li>';
							}
						} else {
							$sublist = '';
							$active = false;
							foreach ($item['submenu'] as $subitem) {
								if ($subitem['page']==$page) {$active=true;}
								$sublist .= '<li class="'.($subitem['page']==$page?'active':NULL).'"><a href="?page='.$subitem['page'].'">'.$subitem['title'].'</a></li>';
							}
							echo '<li>';
							echo '<a href="#'.$item['page'].'Submenu" class="dropdown-toggle" data-bs-toggle="collapse" aria-expanded="'.($active?'true':'false').'">'.$item['title'].'</a>';
							echo '<ul class="collapse '.($active?'show':'').' list-unstyled" id="'.$item['page'].'Submenu">';
							echo $sublist;
							echo '</ul></li>';
						}
					}
				}
				?>
			</ul>
		</nav>

		<!-- Navbar content  -->
		<div id="content">
			<nav class="navbar navbar-expand-lg">
				<div class="container-fluid stickybar">
					<button type="button" id="sidebarCollapse" class="btn">
						<i class="bi bi-menu-button-fill"></i>
						<span></span>
					</button>
					<button class="btn d-inline-block d-lg-none ml-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<i class="bi bi-three-dots-vertical"></i>
					</button>

					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="nav navbar-nav ms-auto">
							<li class="nav-item">
								<a class="nav-link active" href="#">Page</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Page</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Page</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Page</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>

			<!-- Import main page content  -->
			<?php
				if ($page) {
					include('pages/'.$page.'.php');
				} else {
					echo '<div class="alert alert-danger"><strong>'._('Error 404').'</strong><br><br>'._('Requested page not found.')."</div>";
				}
				#if (in_array($_SESSION['role'], $item['role'])) {

				#} else {
				#	echo '<div class="alert alert-danger"><strong>'._('Access denied').'</strong><br>'._('You don\'t have the right to access this page.')."</div>";
				#}
			?>

		</div>
	</div>
</body>
</html>

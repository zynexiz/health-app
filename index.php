<?php
	/* Setup everything, import configuration and connect to database
	 */
	include_once('include/functions.php');
	include_once('config/config.php');

	session_name(SESSIONID);
	session_start();
	setLanguage(isset($_SESSION['lang']) ? $_SESSION['lang'] : LANG);
	$_SESSION['role'] = isset($_SESSION['role']) ? $_SESSION['role'] : 0;

	# Add access log to database
	addLogData();
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
 		<link rel="stylesheet" href="assets/css/<?php echo isset($_SESSION['theme']) ? $_SESSION['theme'] : THEME; ?>">

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
			array('title' => _('Dashboard'), 'icon' => 'bi-grid-1x2', 'page' => 'home', 'role' => [0,1,2]),
			array('title' => _('New measurement'), 'icon' => 'bi-stopwatch', 'page' => 'addmeasurements', 'role' => [2]),
			array('title' => _('Register'), 'icon' => 'bi-person-plus-fill', 'page' => 'register', 'role' => [0]),
			array('title' => _('Login'), 'icon' => 'bi-box-arrow-in-right', 'page' => 'login', 'role' => [0]),
			array('title' => _('Logout'), 'icon' => 'bi-box-arrow-in-right', 'page' => 'logout', 'role' => [1,2]),
 			array('title' => _('About'), 'icon' => 'bi-info-circle-fill', 'page' => 'about', 'role' => [0,1,2]),
			array('title' => _('Account'), 'page' => '_head', 'role' => [1,2]),
			array('title' => _('My goals'), 'icon' => 'bi-trophy', 'page' => 'usergoals', 'role' => [2]),
			array('title' => _('Account settings'), 'icon' => 'bi-gear', 'page' => 'usersettings', 'role' => [1,2]),
 			array('title' => _('Admin'), 'page' => '_head', 'role' => [1]),
			array('title' => _('User admin'), 'icon' => 'bi-gear', 'page' => 'useradmin', 'role' => [1], 'submenu' =>
				array(
					array('title' => _('Edit users'), 'icon' => 'bi-people', 'page' => 'edituser', 'role' => [1]),
					array('title' => _('Add user'), 'icon' => '', 'page' => 'adduser', 'role' => [1])
				),
			),
			array('title' => _('View logs'), 'icon' => 'bi-gear', 'page', 'page' => 'logdata', 'role' => [1]),
			array('title' => _('Settings'), 'icon' => 'bi-gear', 'page', 'page' => 'settings', 'role' => [1]),
		);
	?>
	<div class="wrapper">
		<!-- Build sidebar from $menuStructure array -->
		<nav id="sidebar">
			<div class="sidebar-header">
			<div class="face-image" style="background-image: url('<?php echo ($_SESSION['role'] == 0) ? "media/logo_icon.png" : "media/faces/default_male.png" ?>')"></div>
				<?php
					echo '<p class="face-text">';
					echo ($_SESSION['role'] == 0) ? '' : _("Logged in as") . ' ' . $_SESSION['firstname'];
					echo '</p>';
				?>
			</div>
			<ul class="list-unstyled"> <?php
				$page = verifyData(isset($_GET['page'])?$_GET['page']:'home',"page", false);
				foreach ($menuStructure as $item) {
					if (in_array($_SESSION['role'], $item['role'])) {
						if (!isset($item['submenu'])) {
							if ($item['page'] == '_head') {
								echo '<p>'.$item['title'].'</p>';
							} else {
								echo '<li class="'.($item['page']==$page?'active':'').'"><a class="'.(isset($item['icon'])?$item['icon']:'').'" href="?page='.$item['page'].'">&nbsp;&nbsp;&nbsp;'.$item['title'].'</a></li>';
								if ($item['page']==$page) { $currentPage = $item['title']; }
							}
						} else {
							$sublist = '';
							$active = false;
							foreach ($item['submenu'] as $subitem) {
								if ($subitem['page']==$page) {$active=true;}
								$sublist .= '<li class="'.($subitem['page']==$page?'active':NULL).'"><a class="'.(isset($item['icon'])?$item['icon']:'').'"href="?page='.$subitem['page'].'">&nbsp;&nbsp;&nbsp;'.$subitem['title'].'</a></li>';
							}
							echo '<li>';
							echo '<a class="dropdown-toggle '.(isset($item['icon'])?$item['icon']:'').'"href="#'.$item['page'].'Submenu" data-bs-toggle="collapse" aria-expanded="'.($active?'true':'false').'">&nbsp;&nbsp;&nbsp;'.$item['title'].'</a>';
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
								<?php echo $currentPage ?>
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

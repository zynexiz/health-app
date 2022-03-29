<?php
	include_once('include/functions.php');
	setLanguage("sv_SE.UTF8");
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
 		<link rel="stylesheet" href="assets/css/color_dark.css">
 		<link rel="stylesheet" href="assets/css/customscrollbar.min.css">

 		<!-- Import scripts styles -->
 		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="assets/bootstrap/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="assets/js/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
		 * Main page is fetch from pages/<pagename>.php
		 *   array('title' => 'Menu text', 'page' => 'pagename')
		 *
		 * Insert a section title in the menu.
		 *   array('title' => 'Text', 'page' => '_head'),
		 *
		 * Build a collapsible menu.
		 *   array('title' => 'Sub menu title', 'page' => 'submenu ID', 'submenu' => array(
		 *     array('title' => 'Sub menu text', 'page' => 'pagename'), ... )
		 */
		$menuStructure=array(
			array('title' => _('Home'), 'page' => 'home'),
			array('title' => _('Accounts'), 'page' => '_head'),
			array('title' => _('Register'), 'page' => 'register'),
			array('title' => _('Login'), 'page' => 'login'),
 			array('title' => _('About'), 'page' => 'about')
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
				$page = isset($_GET['page']) ? $_GET['page'] : "main";
				foreach ($menuStructure as $item) {
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
				?>
			</ul>
		</nav>

		<!-- Navbar content  -->
		<div id="content">
			<nav class="navbar navbar-expand-lg">
				<div class="container-fluid">
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
				$page = verifyData(isset($_GET['page'])?$_GET['page']:'home',"page");
				include('pages/'.$page.'.php');
			?>

		</div>
	</div>
</body>
</html>

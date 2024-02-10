<style>
	.mainLogo{
		width: 100px !important;
	}
	.navbar> .container .navbar-brand{
		margin-right: 96px !important;
	}
	@media(max-width: 768px){
		.mainLogo{
			width: 75px !important;
		}
	}
</style>
<!-- Nav Section -->
<header>
	<!-- Nav Section -->
	<nav class="navbar navbar-default navbar-fixed-top nav-area" id="scroll-menu">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo e(route('home')); ?>"><img src="<?php echo e(asset('public/web/img/logo.png')); ?>" alt="responsive img" class="mainLogo"></a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					
					<li class="<?php echo e(((Request::is('/')) ? 'active' : ' ')); ?>">
						<a href="<?php echo e(route('home')); ?>">Home</a>
					</li>
					<li class="<?php echo e(((Request::is('jobOpening')) ? 'active' : ' ')); ?>">
						<a href="<?php echo e(route('jobOpening')); ?>">Job Openings</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About Us</a>
						<ul class="dropdown-menu">
							<li class="<?php echo e(((Request::is('company-history')) ? 'active' : ' ')); ?>">
								<a href="<?php echo e(route('companyHistory')); ?>">Company History</a>
							</li>
							<li class="<?php echo e(((Request::is('license')) ? 'active' : ' ')); ?>">
								<a href="<?php echo e(route('license')); ?>">Compliance</a>
							</li>
							<li class="<?php echo e(((Request::is('facilities')) ? 'active' : ' ')); ?>">
								<a href="<?php echo e(route('facilities')); ?>">Facilities</a>
							</li>
							<li class="<?php echo e(((Request::is('services')) ? 'active' : ' ')); ?>">
								<a href="<?php echo e(route('services')); ?>">Services</a>
							</li>
							<li class="<?php echo e(((Request::is('organizationChart')) ? 'active' : ' ')); ?>">
								<a href="<?php echo e(route('organizationChart')); ?>">Organization Chart</a>
							</li>
							<li class="<?php echo e(((Request::is('missionVision')) ? 'active' : ' ')); ?>">
								<a href="<?php echo e(route('missionVision')); ?>">Mission & Vision</a>
							</li>
						</ul>
					</li>
					
					<li class="<?php echo e(((Request::is('ourClients')) ? 'active' : ' ')); ?>">
						<a href="<?php echo e(route('ourClients')); ?>">Our Clients</a>
					</li>
					<li class="<?php echo e(((Request::is('galleryAlbum')) ? 'active' : ' ')); ?>">
						<a href="<?php echo e(route('galleryAlbum')); ?>">Gallery</a>
					</li>
					
					<li class="<?php echo e(((Request::is('contact')) ? 'active' : ' ')); ?>"><a href="<?php echo e(route('contact')); ?>">Contact Us</a></li>
					<li class="<?php echo e(((Request::is('noticeBoard')) ? 'active' : ' ')); ?>"><a href="<?php echo e(route('noticeBoard')); ?>">Notice Board</a></li>
					<li>
						<a><img src="<?php echo e(asset('public/web/img/bd_flag.gif')); ?>" width="50" alt=""></a>
					</li>
				</ul>
				
			</div>
			<!--/.nav-collapse -->
		</div>
	</nav>
</header><?php /**PATH /home/eastymap/public_html/resources/views/web/includes/nav.blade.php ENDPATH**/ ?>
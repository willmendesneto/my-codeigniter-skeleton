<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php
	foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
	color: blue;
	text-decoration: none;
	font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>

</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="javascript:void(0);">Grocery CRUD</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li><a href="<?php echo site_url('grocerycrud/film_management')?>" >Films</a></li>
						<li><a href="<?php echo site_url('grocerycrud/products_management')?>" >Products</a></li>
						<li><a href="<?php echo site_url('grocerycrud/orders_management')?>" >Orders</a></li>
						<li><a href="<?php echo site_url('grocerycrud/employees_management')?>" >Employees</a></li>
						<li><a href="<?php echo site_url('grocerycrud/offices_management')?>" >Offices</a></li>
						<li><a href="<?php echo site_url('grocerycrud/customers_management')?>" >Customers</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div style='height:20px;'></div>
	<div>
		<?php echo $output; ?>
	</div>

	<?php
	if(isset($dropdown_setup)) {
		$this->load->view('dependent_dropdown', $dropdown_setup);
	}
	?>

</body>
</html>
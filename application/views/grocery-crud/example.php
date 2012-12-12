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
<div><a href='<?php echo site_url('grocerycrud/customers_management')?>'>Customers</a></div>

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
<html>
<body>
	<h1>Activación de cuenta <?php echo lang('email_activate_heading'), $identity; ?></h1>
	<p><?php echo lang('email_activate_subheading');
	echo anchor(base_url().'index.php/auth/activate/'. $id .'/'. $activation, lang('email_activate_link'));?></p>
</body>
</html>
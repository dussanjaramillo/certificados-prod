<html>
<body>
	<h1><?php echo sprintf(lang('email_forgot_password_heading'), $identity);?></h1>
	<p><?php echo sprintf(lang('email_forgot_password_subheading'), anchor(base_url().'index.php/auth/reset_password/'. $PASSWORDCODE, lang('email_forgot_password_link')));?></p>
</body>
</html>
<h1>Login</h1>
<p>Please login with your email/username and password below.</p>

<div id="infoMessage"><?php echo $message;?></div>


 <div class="row">
    <div class="span4 offset4">
    <h1 class="offset1">Login</h1>
    <p>Please login with your email/username and password below.</p>
      <form action="http://codeigniter.dev/auth/login" class="well center" method="post" accept-charset="utf-8">

        <div class="input-prepend">
          <label for="password">Username:</label>
          <span class="add-on"><i class="icon-user"></i></span>
          <input type="text" value="username" class="input-xlarge" placeholder="username" autofocus="true" />

        </div>

        <div class="input-prepend">
          <label for="password">Password:</label>
          <span class="add-on"><i class="icon-lock"></i></span>
          <input type="password" value="password" class="input-xlarge" placeholder="password" autofocus="true" />

        </div>
        <div class="input-prepend">
          <label for="remember">Remember Me:</label>
          <input type="checkbox" name="remember" value="1" id="remember" />

        </div>
        <input type="submit" name="submit" value="Login" class="btn btn-primary" />

      </form>

      <p><a href="/testtwig/forgot_password">Forgot your password?</a></p>
    </div>
  </div>

<?php echo form_open("auth/login");?>

  <p>
    <label for="identity">Email/Username:</label>
    <?php echo form_input($identity);?>
  </p>

  <p>
    <label for="password">Password:</label>
    <?php echo form_input($password);?>
  </p>

  <p>
    <label for="remember">Remember Me:</label>
    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
  </p>


  <p><?php echo form_submit('submit', 'Login', 'class="btn btn-primary"');?></p>

<?php echo form_close();?>

<p><a href="forgot_password">Forgot your password?</a></p>

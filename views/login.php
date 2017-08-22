<!-- Begin page content -->
<div class="container login-form">
    <div class="row">
    	<div class="col-md-6 offset-md-3">
    		<h5>Please login to continue...</h5>
    		<hr/>
    		<?php 
				if(isset($_SESSION['auth_errors']) && isset($_SESSION['auth_errors']['login'])): 
					App\lib\Helper::showErrors($_SESSION['auth_errors']['login']);
				endif;
			?>
    		<form id="form_login" method="post" action="./index.php">
    			<div class="form-group">
    				<label>Email</label>
    				<input type="email" name="email" class="form-control" placeholder="Email address" required />
    				<small class="form-text text-muted">We'll never share your email with anyone else.</small>
    				<?php 
    					if(isset($_SESSION['auth_errors']) && isset($_SESSION['auth_errors']['email'])): 
    						App\lib\Helper::showErrors($_SESSION['auth_errors']['email']);
    					endif;
    				?>
	    		</div>
	    		<div class="form-group">
	    			<label>Password</label>
	    			<input type="password" name="password" class="form-control" placeholder="Password" required />
	    			<?php 
    					if(isset($_SESSION['auth_errors']) && isset($_SESSION['auth_errors']['password'])): 
    						App\lib\Helper::showErrors($_SESSION['auth_errors']['password']);
    					endif;
    				?>
	    		</div>
	    		<div class="form-group">
                    <a href="./register.php" class="btn btn-primary pull-left" title="Not a member yet? Register here!">
                        <i class="fa fa-user"></i> Register
                    </a>
	    			<button class="btn btn-success pull-right" name="form_login_submit" value="submit">
	    				<i class="fa fa-sign-in"></i> Login
	    			</button>
	    		</div>
    		</form>
    	</div>	
    </div>
</div>
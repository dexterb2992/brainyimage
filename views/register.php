<!-- Begin page content -->
<div class="container login-form">
    <div class="row">
    	<div class="col-md-6 offset-md-3">
    		<h4 class="text-success">Register</h4>
    		<hr/>
    		<?php 
				if(isset($_SESSION['reg_errors']) && isset($_SESSION['reg_errors']['login'])): 
					App\lib\Helper::showErrors($_SESSION['reg_errors']['login']);
				endif;
			?>
    		<form id="form_register" method="post" action="./register.php">
    			<div class="form-group">
    				<label class="text-muted">Name</label>
    				<input type="text" name="name" class="form-control" placeholder="Name" required />
    				<?php 
    					if(isset($_SESSION['reg_errors']) && isset($_SESSION['reg_errors']['name'])): 
    						App\lib\Helper::showErrors($_SESSION['reg_errors']['name']);
    					endif;
    				?>
    			</div>

    			<div class="form-group">
    				<label class="text-muted">Email</label>
    				<input type="email" name="email" class="form-control" placeholder="Email address" required />
    				<small class="form-text text-muted">We'll never share your email with anyone else.</small>
    				<?php 
    					if(isset($_SESSION['reg_errors']) && isset($_SESSION['reg_errors']['email'])): 
    						App\lib\Helper::showErrors($_SESSION['reg_errors']['email']);
    					endif;
    				?>
	    		</div>

	    		<div class="form-group">
	    			<label class="text-muted">Password</label>
	    			<input type="password" name="password" class="form-control" placeholder="Password" required />
	    			<?php 
    					if(isset($_SESSION['reg_errors']) && isset($_SESSION['reg_errors']['password'])): 
    						App\lib\Helper::showErrors($_SESSION['reg_errors']['password']);
    					endif;
    				?>
	    		</div>

	    		<div class="form-group">
    				<label class="text-muted">Confirm Password</label>
    				<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required />
    				<?php 
    					if(isset($_SESSION['reg_errors']) && isset($_SESSION['reg_errors']['password_confirmation'])): 
    						App\lib\Helper::showErrors($_SESSION['reg_errors']['password_confirmation']);
    					endif;
    				?>
    			</div>

    			<div class="form-group">
    				<label class="text-muted">License Key</label>
    				<input type="text" name="license_key" class="form-control" placeholder="License Key" required />
    				<?php 
    					if(isset($_SESSION['reg_errors']) && isset($_SESSION['reg_errors']['license_key'])): 
    						App\lib\Helper::showErrors($_SESSION['reg_errors']['license_key']);
    					endif;
    				?>
    			</div>

	    		<div class="form-group">
		    		<a href="./login.php" class="btn btn-primary pull-left" title="Already a member? Login here!">
		    			<i class="fa fa-sign-in"></i> Login
		    		</a>
	    			<button class="btn btn-success pull-right" name="form_register_submit" value="submit">
	    				<i class="fa fa-send"></i> Submit
	    			</button>
	    		</div>
    		</form>
    	</div>	
    </div>
</div>
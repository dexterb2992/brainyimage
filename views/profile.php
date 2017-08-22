<!-- Begin page content -->
<div class="container login-form">
    <div class="row">
    	<div class="col-md-6 offset-md-3">
    		<h4 class="text-success">My Account</h4>
    		<hr/>
    		<?php
    			$user = $_SESSION['user'];
    			if(isset($_SESSION['_errors']) && isset($_SESSION['_errors']['profile'])){
					App\lib\Helper::showErrors($_SESSION['_errors']['profile']);
    			}

    			if( isset($_SESSION['_flash_message']) ){
    				App\lib\Helper::showErrors($_SESSION['_flash_message'], 'success');
    			}

    			if( isset($_SESSION['last_inputs']) ){
    				$last_q_value = isset($_SESSION['last_inputs']['q']) ? $_SESSION['last_inputs']['q'] : '';
    			}
    		?>
    		<div class="row">
    			<div class="col-md-12">
    				<ul class="nav nav-tabs">
		    			<li class="nav-item">
		    				<a href="#form_profile" class="nav-link <?= !isset($last_q_value) ? 'active' : $last_q_value == 'general' ? 'active' : ''?>" data-toggle="tab" role="tab" aria-controls="general_info">General Info</a>
		    			</li>
		    			<li class="nav-item">
		    				<a href="#form_password" class="nav-link <?= isset($last_q_value) && $last_q_value == 'update_password' ? 'active' : ''?>" data-toggle="tab" aria-controls="password_settings">Password Settings</a>
		    			</li>
		    			<li class="nav-item">
		    				<a href="#form_avatar" class="nav-link <?= isset($last_q_value) && $last_q_value == 'avatar' ? 'active' : ''?>" data-toggle="tab" aria-controls="avatar">Avatar</a>
		    			</li>
		    		</ul>
    			</div>
    		</div>
    		<br/>
    		<div class="row">
    			<div class="col-md-12">
		    		<div class="tab-content">
		    			<form id="form_profile" class="tab-pane <?= !isset($last_q_value) ? 'active' : $last_q_value == 'general' ? 'active' : ''?>" role="tabpanel" method="post" action="./profile.php">
			    			<div class="form-group">
			    				<label class="text-muted">Name</label>
			    				<input type="text" name="name" value="<?= $user['name']; ?>" class="form-control" required/>
			    			</div>
			    			<div class="form-group">
			    				<label class="text-muted">Email</label>
			    				<input type="email" name="email" value="<?= $user['email']; ?>" class="form-control" readonly/>
			    				<small class="form-text text-muted">Your email address can't be changed.</small>
			    			</div>
			    			<div class="form-group">
			    				<label class="text-muted">License Key</label>
			    				<input type="text" name="access_key" value="<?= $user['access_key']; ?>" class="form-control" required />
			    				<?php 
			    					if(isset($_SESSION['_errors']) && isset($_SESSION['_errors']['access_key'])): 
			    						App\lib\Helper::showErrors($_SESSION['_errors']['access_key']);
			    					endif;
			    				?>
			    			</div>
			    			<div class="form-group">
			    				<label class="text-muted">Membership Type</label>
			    				<input type="text" value="<?= ucfirst($user['subscription_type']); ?>" class="form-control" readonly/>
			    			</div>
			    			<input type="hidden" name="q" value="general" />
			    			<div class="form-group">
			    				<button class="btn btn-success pull-right" type="submit" name="form_profile_submit" value="submit">
			    					<i class="fa fa-check"></i> Save changes
			    				</button>
			    			</div>
			    		</form>

			    		<form id="form_password" class="tab-pane <?= isset($last_q_value) && $last_q_value == 'update_password' ? 'active' : '' ?>" role="tabpanel" method="post" action="./profile.php">
			    			<div class="form-group">
				    			<label class="text-muted">New Password</label>
				    			<input type="password" name="password" class="form-control" placeholder="Password" required />
				    			<?php 
			    					if(isset($_SESSION['_errors']) && isset($_SESSION['_errors']['password'])): 
			    						App\lib\Helper::showErrors($_SESSION['_errors']['password']);
			    					endif;
			    				?>
				    		</div>

				    		<div class="form-group">
			    				<label class="text-muted">Confirm Password</label>
			    				<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required />
			    				<?php 
			    					if(isset($_SESSION['_errors']) && isset($_SESSION['_errors']['password_confirmation'])): 
			    						App\lib\Helper::showErrors($_SESSION['_errors']['password_confirmation']);
			    					endif;
			    				?>
			    			</div>

			    			<div class="form-group">
			    				<label class="text-muted">Old Password</label>
			    				<input type="password" name="old_password" class="form-control" placeholder="Old Password" required />
			    				<small class="form-text text-muted">Please enter your old password.</small>
			    				<?php 
			    					if(isset($_SESSION['_errors']) && isset($_SESSION['_errors']['old_password'])): 
			    						App\lib\Helper::showErrors($_SESSION['_errors']['old_password']);
			    					endif;
			    				?>
			    			</div>
			    			<input type="hidden" name="q" value="update_password" />
			    			<div class="form-group">
			    				<button class="btn btn-success pull-right" type="submit" name="form_password_submit" value="submit">
			    					<i class="fa fa-check"></i> Save changes
			    				</button>
			    			</div>
			    		</form>

			    		<form id="form_avatar" class="tab-pane <?= isset($last_q_value) && $last_q_value == 'avatar' ? 'active' : ''?>" role="tabpanel" method="post" action="./profile.php" enctype="multipart/form-data">
			    			<div class="form-group">
			    				<img class="img-thumbnail img-responsive" id="avatar_preview" src="<?= $user['avatar'] != "" ? $user['avatar'] : './assets/images/default.svg'; ?>" />
			    			</div>
			    			<div class="form-group">
			    				<input type="file" name="avatar" style="display: none;" id="btn_avatar" accept="image/*" required />
			    				<input type="hidden" name="q" value="avatar" />
			    				<button type="button" class="btn btn-primary btn-sm" id="browse_avatar" onclick="document.getElementById('btn_avatar').click();">
			    					<i class="fa fa-image"></i> Browse
			    				</button>
			    				<button class="btn btn-success btn-sm" type="submit" name="form_avatar_submit" value="submit">
			    					<i class="fa fa-check"></i> Save changes
			    				</button>
			    			</div>
			    		</form>
		    		</div>
			    </div>
    		</div>
    	</div>
    </div>
</div>
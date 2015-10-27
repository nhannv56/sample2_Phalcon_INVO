<?php echo $this->flashSession->output(); ?>
<?php echo $this->tag->form(array('start')); ?>
	<fieldset>
		<div>
			<label for="email">Username/Email</label>
			<div>
				<?php echo $this->tag->textField(array('email')); ?>
			</div>
		</div>
		<div>
			<label for="password">Password</label>
			<div>
				<?php echo $this->tag->passwordField(array('password')); ?>
			</div>
		</div>
		<div>
			<?php echo $this->tag->submitButton(array('Login')); ?>
		</div>
	</fieldset>
</form>
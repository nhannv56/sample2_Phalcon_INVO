	{{flash.output()}}
{{form('start','class':'form-horizontal')}}
	<fieldset>
		<div class="form-group">
			<label for="email" class="control-label col-sm-2">Username/Email</label>
			<div class="col-sm-6 col-xs-4">
				{{text_field('email','class':'form-control','id':'email')}}
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="control-label col-sm-2">Password</label>
			<div class="col-sm-6  col-xs-4">
				{{password_field('password','class':'form-control')}}
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-10">
			{{submit_button('Login','class':'btn btn-default')}}
		</div>
	</fieldset>
</form>
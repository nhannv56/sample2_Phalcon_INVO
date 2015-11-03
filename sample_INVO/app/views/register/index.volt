<div class="page-header">
    <h1>Register page</h1>
</div>
{{flash.output()}}
{{form('regis')}}
	<fieldset>
		<div>
			<label for="email">Username/Email</label>
			<div>
				{{text_field('email')}}
			</div>
		</div>
		<div>
			<label for="password">Password</label>
			<div>
				{{text_field('password')}}
			</div>
		</div>
		<div>
			<label for="first_name">First name</label>
			<div>
				{{text_field('first_name')}}
			</div>
		</div>
		<div>
			<label for="last_name">Last name</label>
			<div>
				{{text_field('last_name')}}
			</div>
		</div>
		<div>
			<label for="bithday">Bithday</label>
			<div>
				{{date_field('bithday')}}
			</div>
		</div>
		<div>
			{{submit_button('Register')}}
		</div>
	</fieldset>
</form>
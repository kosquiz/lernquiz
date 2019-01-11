
<head>
<link rel="stylesheet" href="LogIn.css">
</head>
<?php

class Output{

	public function debug(){
		$this->loginOutput(true);
	}

	public function indexOutput($variables){

		?>




		<?php

		$this->footerOutput();
	}

	public function footerOutput(){

	}

	public function loginOutput($variables){
		?>
		<div>
			<div>
		<h2>Login</h2>
		</div>
		<div>
<p>Username</p><br><br>
<p>Passwort</p>
		</div>
		<div>

		</div>
		</div>
		<?php
	}


	public function registerOutput($variables){

	}

	public function gameBoardOutput($variables){

	}

}

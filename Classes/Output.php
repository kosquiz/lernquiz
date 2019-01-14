
<head>
<link rel="stylesheet" href="LogIn.css">
<link
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
				<form id="loginform">
					<div class="log">
		<h2 class="logtext">Login</h2>
		<label for="user"><b class="logtext">Benutzername:</b></label>
		    <input type="text" placeholder="Benutzer eingeben" name="user" required>
				<br>
		    <label for="psw"><b class="logtext">Passwort:</b></label>
		    <input type="password" placeholder="Passwort eingeben" name="psw" required><br>
		    <button type="submit">Login</button><button onclick="window.location.href='/index.php?site=register'">Registrieren</button>
		</div>
	</form>
	<script> src="quiz.js"</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<?php
	}


	public function registerOutput($variables){

	}

	public function gameBoardOutput($variables){

	}

}

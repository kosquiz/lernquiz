
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
				<form action="index.php?site=doLogin" method="post" id="loginform">
					<div class="log">
		<h2 class="logtext">Login</h2>
		<label for="user"><b class="logtext">Benutzername:</b></label>
		    <input type="text" placeholder="Benutzer eingeben" name="user" required>
				<br>
		    <label for="pass"><b class="logtext">Passwort:</b></label>
		    <input type="password" placeholder="Passwort eingeben" name="pass" required><br>
		    <button type="submit">Login</button></form><button onclick="window.location.href='index.php?site=register'">Registrieren</button>
		</div>
	<script> src="quiz.js"</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<?php
	}


	public function registerOutput($error){
?>
<form>
	<div>
<h2 class="logtext">Registrieren</h2>
<label for="user"><b class="logtext">Bitte Benutzername eingeben:</b></label>
<div>
<input type="text" placeholder="Benutzer eingeben" name="user" required></div>
<br>
<label for="pass"><b class="logtext">Bitte geben Sie ein Passwort ein:</b></label>
<div><input type="password" placeholder="Passwort eingeben" name="pass" required><div><br>
<button type="submit">Registrieren</button></form><button onclick="window.location.href='index.php?site=login'">Zurück zum Login</button>
</div>
<?php

	}

	public function gameBoardOutput(){
?>
<div class="chatbox">
	<div class="written"></div>
<input class="write" placeholder="Schreiben Sie eine Nachricht">
<div class="center"><button type="button">Senden</button>
<label><?php  ?></label></div>
</div>

<?php
	}

}

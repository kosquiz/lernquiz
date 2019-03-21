

<?php

class Output{

	public function debug(){
		$this->loginOutput(true);
	}

	public function indexOutput($variables){
	/*echo "<pre>";
		print_r($variables);
		echo "</pre>";*/
		?>
		<link rel="stylesheet" href="Style.css">
		<header class="head">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
		    <polygon class="svg--sm" fill="white" points="10,10 30,100 65,21 90,100 100,75 100,100 0,100"/>
		    <polygon class="svg--lg" fill="white" points="10,10 15,100 33,21 45,100 50,75 55,100 72,20 85,100 95,50 100,80 100,100 0,100" />
		  </svg>
		<div>
		<div style="width: 40%; height: 100%; float: left">
			<h1>Räume</h1>
		</div>
		</div>
		</header>

		<div style="margin-left: 2rem">
<form action="index.php?site=createRoom" method="post" style="margin-top: 1.5rem">
<input type="text" name="roomName" placeholder="Geben Sie einen Namen für den Raum ein" maxlength="45" style="width: 16rem"></input>
<p>(max. 45 Zeichen)</p>
<div style="margin-top: 20px; margin-bottom: 20px">
<button class="btn" type="submit">Raum erstellen</button>
<input type="checkbox" name="private"> Privater Raum</input>
</div>
<p>Raum Passwort:</p>
<input type="text" name="roomPassword" placeholder="Geben Sie ein Passwort für den Raum ein" maxlength="45" style="width: 16rem"></input><p>(Feld Optional)</p>
</form>

		<?php

		foreach($variables['rooms'] as $room):
			if($room['isActive']==1):?>
			<div class="room">
			<p>Ersteller: <?php echo $room['Accounts_Username']; ?>
			<P>Raummname: <?php echo $room['GameRoomName'];?></p>
				<P>ID: <?php echo $room['idGameRoom'];?></p>
				<form action="index.php?site=joinRoom" method="post">
					<input name="roomID" type="hidden" value="<?php echo $room['idGameRoom'];?>"></input>
				<button type="submit" style="float: right; margin-right: 20px">Beitreten</button>
				<?php
				if(array_key_exists('Password', $room)):?>
					<input type="password" placeholder="Passwort eingeben" name="password">
				<?php
			endif;?>
	</form>
		</div>
	<?php
			endif;
		endforeach;
?>
<button class="btn" type="submit" onclick="window.location.href='index.php?site=logout'">Logout</button>
</div>
<?php
	}

	public function loginOutput($variables){
		?>
		<link rel="stylesheet" href="Style.css">
				<form action="index.php?site=doLogin" method="post" id="loginform">
					<div class="log">
		<h2 class="logtext">Login</h2>
		<label for="user"><b class="logtext">Benutzername:</b></label>
		    <input type="text" placeholder="Benutzer eingeben" name="user" required>
				<br>
		    <label for="pass"><b class="logtext">Passwort:</b></label>
		    <input type="password" placeholder="Passwort eingeben" name="pass" required><br>
		    <button type="submit">Login</button></form><button type="button" onclick="window.location.href='index.php?site=register'">Registrieren</button>
		</div>
		<?php
	}

	public function registerOutput($error){
?>
<link rel="stylesheet" href="Style.css">
<form action="index.php?site=doRegister" method="post">
	<div>
<h2 class="logtext">Registrieren</h2>
<label for="user"><b class="logtext">Bitte Benutzername eingeben:</b></label>
<div>
<input type="text" placeholder="Benutzer eingeben" name="user" required></div>
<br>
<label for="pass"><b class="logtext">Bitte geben Sie ein Passwort ein:</b></label>
<div><input type="password" placeholder="Passwort eingeben" name="pass" required><div><br>
<button type="submit">Registrieren</button></form><button type="button" onclick="window.location.href='index.php?site=login'">Zurück zum Login</button>
</div>
<?php

	}

	public function gameBoardOutput(){
?>
<div id="gameBoard">
<link rel="stylesheet" href="Style.css">
<!-- Start Header -->
<header class="head">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
    <polygon class="svg--sm" fill="white" points="10,10 30,100 65,21 90,100 100,75 100,100 0,100"/>
    <polygon class="svg--lg" fill="white" points="10,10 15,100 33,21 45,100 50,75 55,100 72,20 85,100 95,50 100,80 100,100 0,100" />
  </svg>
<div>
<div style="width: 40%; height: 100%; float: left">
	<h1>Raum "<?php echo 'test';?>" erstellt von "<?php echo 'test';?>"</h1>
</div>
</div>
</header>
<!-- End Header -->

<!-- Start Dashboard -->

<div style="width:75%">
<div class="dashboard">
<table style="width: 100%; height: 100%"><?php
	for($f=0; $f<5; $f++):
		?><tr><?php
		for($s=0; $s<4; $s++):
?>
<td class="question number<?php echo ($f*4+$s+1);?>" data-id="<?php echo ($f*4+$s+1);?>">
</td>
<?php
		endfor;
		?></tr><?php
	endfor;
	?></table>
</div>

<!-- Start Answers -->
<div id="answerPosition">
<table style="width: 100%;">
<?php
	for($a=0; $a<2; $a++):
 	?>
 		<tr>
	 	<?php
	 		for($c=0; $c<2; $c++):?>
	 	<td class="answer number<?php echo ($a*2+$c+1);?>" data-id="<?php echo ($a*2+$c+1);?>">

	 	</td>
		<?php
	endfor; ?>
 </tr>
<?php
endfor; ?>
</table>
</div>
</div>
<!-- End Answers -->

<!-- End Dashboard -->

<!-- start chatbox-->
<div class="chatbox">
	<div class="written" id="chatBox">
</div>
<div class="playerView"></div>
<input class="write" id="chatInput" placeholder="Schreiben Sie eine Nachricht">
<button class="send" id="chatSubmit" type="button">Senden</button>
</div>
<div class="backBtns">
	<button class="Logout" type="submit" onclick="window.location.href='index.php?site=logout'">Logout</button>
	<button class="Logout" type="submit" onclick="window.location.href='index.php?site=leaveRoom'">Raum Verlassen</button>
</div>
</div>
<!-- end chatbox-->



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="quiz.js"></script>
<?php
	}
}

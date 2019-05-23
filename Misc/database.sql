SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema kosquiz
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema kosquiz
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `kosquiz` DEFAULT CHARACTER SET utf8 ;
USE `kosquiz` ;

-- -----------------------------------------------------
-- Table `kosquiz`.`GameRoom`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`GameRoom` (
  `idGameRoom` INT NOT NULL AUTO_INCREMENT,
  `GameRoomName` VARCHAR(45) NULL,
  `isActive` TINYINT NULL,
  `isPrivate` TINYINT NOT NULL,
  `Password` VARCHAR(45) NULL,
  `Accounts_Username` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`idGameRoom`),
  INDEX `fk_GameRoom_Accounts1_idx` (`Accounts_Username` ASC),
  CONSTRAINT `fk_GameRoom_Accounts1`
    FOREIGN KEY (`Accounts_Username`)
    REFERENCES `kosquiz`.`Accounts` (`Username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`Accounts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`Accounts` (
  `Username` VARCHAR(16) NULL,
  `Password` VARCHAR(2000) NOT NULL,
  `LastActivity` DATETIME NOT NULL,
  `GameRoom_idGameRoom` INT NULL,
  PRIMARY KEY (`Username`),
  INDEX `fk_Accounts_GameRoom1_idx` (`GameRoom_idGameRoom` ASC),
  CONSTRAINT `fk_Accounts_GameRoom1`
    FOREIGN KEY (`GameRoom_idGameRoom`)
    REFERENCES `kosquiz`.`GameRoom` (`idGameRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`ChatMessage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`ChatMessage` (
  `idChat` INT NOT NULL AUTO_INCREMENT,
  `Time` DATETIME NOT NULL,
  `Message` VARCHAR(240) NOT NULL,
  `Accounts_Username` VARCHAR(16) NOT NULL,
  `GameRoom_idGameRoom` INT NOT NULL,
  PRIMARY KEY (`idChat`),
  INDEX `fk_ChatMessage_Accounts1_idx` (`Accounts_Username` ASC),
  INDEX `fk_ChatMessage_GameRoom1_idx` (`GameRoom_idGameRoom` ASC),
  CONSTRAINT `fk_ChatMessage_Accounts1`
    FOREIGN KEY (`Accounts_Username`)
    REFERENCES `kosquiz`.`Accounts` (`Username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ChatMessage_GameRoom1`
    FOREIGN KEY (`GameRoom_idGameRoom`)
    REFERENCES `kosquiz`.`GameRoom` (`idGameRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`Score`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`Score` (
  `idScore` INT NOT NULL AUTO_INCREMENT,
  `Date` DATE NOT NULL,
  `Score` INT NOT NULL,
  `Accounts_Username` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`idScore`),
  INDEX `fk_Score_Accounts1_idx` (`Accounts_Username` ASC),
  CONSTRAINT `fk_Score_Accounts1`
    FOREIGN KEY (`Accounts_Username`)
    REFERENCES `kosquiz`.`Accounts` (`Username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`Question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`Question` (
  `idQuestion` INT NOT NULL AUTO_INCREMENT,
  `Category` VARCHAR(32) NOT NULL,
  `Question` VARCHAR(1000) NOT NULL,
  `Difficulty` TINYINT NOT NULL,
  PRIMARY KEY (`idQuestion`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`Answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`Answer` (
  `idAnswer` INT NOT NULL AUTO_INCREMENT,
  `Answer` VARCHAR(1000) NOT NULL,
  `Question_idQuestion` INT NOT NULL,
  `Correct` TINYINT NULL,
  PRIMARY KEY (`idAnswer`),
  INDEX `fk_Answer_Question1_idx` (`Question_idQuestion` ASC),
  CONSTRAINT `fk_Answer_Question1`
    FOREIGN KEY (`Question_idQuestion`)
    REFERENCES `kosquiz`.`Question` (`idQuestion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`Game`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`Game` (
  `idGame` INT NOT NULL AUTO_INCREMENT,
  `GameRoom_idGameRoom` INT NOT NULL,
  PRIMARY KEY (`idGame`),
  INDEX `fk_Game_GameRoom1_idx` (`GameRoom_idGameRoom` ASC),
  CONSTRAINT `fk_Game_GameRoom1`
    FOREIGN KEY (`GameRoom_idGameRoom`)
    REFERENCES `kosquiz`.`GameRoom` (`idGameRoom`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`GameLog`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `kosquiz`.`GameLog` (
  `idGameLog` INT NOT NULL AUTO_INCREMENT,
  `EventName` VARCHAR(200) NOT NULL,
  `Date` DATETIME NOT NULL,
  `Game_idGame` INT NOT NULL,
  `EventVal1` VARCHAR(45) NOT NULL,
  `EventVal2` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idGameLog`),
  INDEX `fk_GameLog_Game1_idx` (`Game_idGame` ASC),
  CONSTRAINT `fk_GameLog_Game1`
    FOREIGN KEY (`Game_idGame`)
    REFERENCES `kosquiz`.`Game` (`idGame`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- -----------------------------------------------------
-- Question Rotations 1
-- -----------------------------------------------------

INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'Aus wievielen Bit besteht eine IPv4 Adresse?', 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("12 Bit", 0, 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("22 Byte", 0, 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("77 Bit", 0, 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("32 Bit", 1, 1);

INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'Welches der folgenden Subnetze ist richtig?', 2);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.0.0.255", 0, 2);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.0.255.0", 0, 2);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.128.0.0", 0, 2);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.255.255.0", 1, 2);

INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'Wie groß ist eine IPv6 Adresse?', 3);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("200 Bit", 0, 3);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("5 Byte", 0, 3);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("64 Bit", 0, 3);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("128 Bit", 1, 3);

INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'Für was steht DNS?', 4);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Domain Nullify Storage", 0, 4);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Dean Nord Stud", 0, 4);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Do Not Surf", 0, 4);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Domain Name Service", 1, 4);


INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 5);

#fängt an mit 11
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Wem ist der Abgeordnete untergeordnet?', 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Bundeskanzler", 0, 11);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Dem Volk", 0, 11);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Seiner Partei", 0, 11);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Seinem Gewissen", 1, 11);

INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Wie oft wird der Bundestag (i.d.R) gewählt?', 2);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Alle 14 Tage", 0, 12);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Drei mal im Jahr", 0, 12);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Sonntag wenns regnet", 0, 12);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Alle 4 Jahre", 1, 12);

INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Wie heisst das Wahlrecht bei dem nur der Wahlkreisgewinner einen Sitzim Parlament bekommt und alle anderen "unter den Tisch" fallen?"', 3);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Versteckter Hut Wahl", 0, 13);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Drei Köpfe Wahl", 0, 13);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Teilheitswahlrecht", 0, 13);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Mehrheitswahlrecht", 1, 13);


INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Was sagt das Verhältniswahlrecht aus?', 4);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Alle 14 Tage", 0, 14);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Drei mal im Jahr", 0, 14);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Sonntag wenns regnet", 0, 14);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Alle 4 Jahre", 1, 14);

INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Von wem wird der Bundespräsident gewählt', 5);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Komplett vom Bundestag", 0, 15);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Direkt vom Volk", 0, 15);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Vom Kanzler", 0, 15);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Von der Bundesversammlung", 1, 15);

INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Was wählt man mit der Zweitstimmt bei der Bundestagswahl', 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Wird nicht gewählt", 0, 16);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Direkt vom Volk", 0, 16);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Vom Kanzler", 0, 16);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Landesliste einer Partei", 1, 16);

INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Welches Geschlecht hat der derzeitige Bundeskanzler?', 2);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Alle drei", 0, 17);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Keins", 0, 17);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Männlich", 0, 17);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Weiblich", 1, 17);


INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Wie nennt man das Wahlrecht zum deutschen Bundestag?', 3);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zermummte Becherwahl", 0, 18);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Versteckte Tonwahl", 0, 18);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Gemeines Allgemeinrecht", 0, 18);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Personalisiertes Verhältniswahlrecht", 1, 18);


INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Welche Farbe wird der CDU zugeordnet?', 4);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Braun", 0, 19);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Grün", 0, 19);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Blau", 0, 19);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Schwarz", 1, 19);

INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Welches Recht hat ein MdB?', 5);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Brinesse Recht", 0, 20);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Impendanzgesetz", 0, 20);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Rechtsfahrrecht", 0, 20);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zeugnissverweigerungsrecht", 1, 20);

#fängt an mit 21
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 5);

#fängt an mit 31
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'Wo setzt die Marktanalyse an', 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zukunft", 0, 31);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Vergangenheit", 0, 31);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zeitraum", 0, 31);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zeitpunkt", 1, 31);

INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'Was ist das Marktvolumen?', 2);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Mögliche Größe", 0, 32);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Theoretische Größe", 0, 32);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Anteil am Volumen", 0, 32);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Tatsächliche Größe", 1, 32);

INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'Was gehört zu der Produktpolitik?', 3);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Handelsbetriebe", 0, 33);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Industriebetriebe", 0, 33);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Keins von beiden", 0, 33);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Industrie- u. Handelsbetriebe", 1, 33);

INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'Der wievielte Ablaufpunkt des Produktlebenszyklus gehört die Sättigung?', 4);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("2", 0, 34);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("1", 0, 34);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("5", 0, 34);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("4", 1, 34);

INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'Welches gehört nicht zur Preisdifferenzierung?', 5);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zeitlich", 0, 35);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Persönlich", 0, 35);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Mengenmäßig", 0, 35);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zusammenarbeitend", 1, 35);

INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'Welches ist kein Werbeträger für ein Produkt?', 1);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Zeitung", 0, 35);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Fahrzeuge", 0, 35);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Internet", 0, 35);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("Fernsehspot", 1, 35);


INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 5);

#fängt an mit 41
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 5);









-- -----------------------------------------------------
-- Question Rotation 2
-- -----------------------------------------------------








insert into accounts VALUES ("admin", "adminhiddenpass", NOW(), NULL);



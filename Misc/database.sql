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
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'Aus wievielen Bit besteht eine IPv4 Adresse?', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'Welches der folgenden Subnetze ist nicht richtig?', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'Wie groß ist eine IPv6 Adresse?', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Wem ist der Abgeordnete nicht untergeordnet?', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Wie oft wird der Bundestag (i.d.R) gewählt?', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Wie heisst das Wahlrecht bei dem nur der Wahlkreisgewinner einen Sitzim Parlament bekommt und alle anderen "unter den Tisch" fallen?"', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'Was sagt das Verhältniswahlrecht aus?', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'DeUMM', 5);


-- -----------------------------------------------------
-- Question Rotation 2
-- -----------------------------------------------------
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('AWP', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('BWP', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('VSY', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('ITS', 'DrUMM', 5);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'DrUMM', 1);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'DUrMM', 2);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'DUMrM', 3);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'DUMMr', 4);
INSERT INTO Question(Category, Question, Difficulty) VALUES('SOZI', 'DeUMM', 5);


INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("12 Bit", 0, 11);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("22 Byte", 0, 11);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("77 Bit", 0, 11);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("32 Bit", 1, 11);


INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.0.0.255", 0, 12);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.0.255.0", 0, 12);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.128.0.0", 0, 12);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("255.255.255.0", 1, 12);


INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("200 Bit", 0, 13);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("5 Byte", 0, 13);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("64 Bit", 0, 13);
INSERT INTO answer (Answer, Correct, Question_idQuestion) VALUES ("128 Bit", 1, 13);







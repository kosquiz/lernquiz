-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema kosquiz
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `kosquiz` ;

-- -----------------------------------------------------
-- Schema kosquiz
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `kosquiz` DEFAULT CHARACTER SET utf8 ;
USE `kosquiz` ;

-- -----------------------------------------------------
-- Table `kosquiz`.`Accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kosquiz`.`Accounts` ;

CREATE TABLE IF NOT EXISTS `kosquiz`.`Accounts` (
  `Username` VARCHAR(16) NULL,
  `Password` VARCHAR(2000) NOT NULL,
  `LastActivity` DATETIME NOT NULL,
  PRIMARY KEY (`Username`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`ChatMessage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kosquiz`.`ChatMessage` ;

CREATE TABLE IF NOT EXISTS `kosquiz`.`ChatMessage` (
  `idChat` INT NOT NULL AUTO_INCREMENT,
  `Time` DATETIME NOT NULL,
  `Message` VARCHAR(240) NOT NULL,
  `Accounts_Username` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`idChat`),
  INDEX `fk_ChatMessage_Accounts1_idx` (`Accounts_Username` ASC),
  CONSTRAINT `fk_ChatMessage_Accounts1`
    FOREIGN KEY (`Accounts_Username`)
    REFERENCES `kosquiz`.`Accounts` (`Username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`Score`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kosquiz`.`Score` ;

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
DROP TABLE IF EXISTS `kosquiz`.`Question` ;

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
DROP TABLE IF EXISTS `kosquiz`.`Answer` ;

CREATE TABLE IF NOT EXISTS `kosquiz`.`Answer` (
  `idAnswer` INT NOT NULL AUTO_INCREMENT,
  `Answer` VARCHAR(1000) NOT NULL,
  `Question_idQuestion` INT NOT NULL,
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
DROP TABLE IF EXISTS `kosquiz`.`Game` ;

CREATE TABLE IF NOT EXISTS `kosquiz`.`Game` (
  `idGame` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idGame`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kosquiz`.`GameLog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kosquiz`.`GameLog` ;

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

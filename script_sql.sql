-- MySQL Script generated by MySQL Workbench
-- Thu Jan  4 22:07:36 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema projetfmablog
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema projetfmablog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projetfmablog` DEFAULT CHARACTER SET utf8 ;
USE `projetfmablog` ;

-- -----------------------------------------------------
-- Table `projetfmablog`.`type_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projetfmablog`.`type_user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type_user_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetfmablog`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projetfmablog`.`user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `last_name` VARCHAR(50) NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `login` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `token` VARCHAR(60) NULL,
  `FK_type_user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_utilisateur_type_utilisateur1_idx` (`FK_type_user_id` ASC),
  CONSTRAINT `fk_utilisateur_type_utilisateur1`
    FOREIGN KEY (`FK_type_user_id`)
    REFERENCES `projetfmablog`.`type_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetfmablog`.`post_statut`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projetfmablog`.`post_statut` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_statut_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetfmablog`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projetfmablog`.`category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetfmablog`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projetfmablog`.`post` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `chapo` VARCHAR(150) NOT NULL,
  `content` TEXT(5000) NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `modification_date` DATETIME NULL,
  `image` VARCHAR(50) NULL,
  `slug` VARCHAR(250) NOT NULL,
  `FK_user_id` INT(11) NOT NULL,
  `FK_post_statut_id` INT(11) NOT NULL,
  `FK_category_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_post_utilisateur_idx` (`FK_user_id` ASC),
  INDEX `fk_post_post_statut1_idx` (`FK_post_statut_id` ASC),
  INDEX `fk_post_category1_idx` (`FK_category_id` ASC),
  CONSTRAINT `fk_post_utilisateur`
    FOREIGN KEY (`FK_user_id`)
    REFERENCES `projetfmablog`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_post_statut1`
    FOREIGN KEY (`FK_post_statut_id`)
    REFERENCES `projetfmablog`.`post_statut` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_category1`
    FOREIGN KEY (`FK_category_id`)
    REFERENCES `projetfmablog`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetfmablog`.`comment_statut`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projetfmablog`.`comment_statut` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_statut_name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projetfmablog`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projetfmablog`.`comment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(250) NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `modification_date` DATETIME NULL,
  `FK_statut_comment_id` INT(11) NOT NULL,
  `FK_user_id` INT(11) NOT NULL,
  `FK_post_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_commentaire_utilisateur1_idx` (`FK_user_id` ASC),
  INDEX `fk_commentaire_post1_idx` (`FK_post_id` ASC),
  INDEX `fk_commentaire_statut_commentaire1_idx` (`FK_statut_comment_id` ASC),
  CONSTRAINT `fk_commentaire_utilisateur1`
    FOREIGN KEY (`FK_user_id`)
    REFERENCES `projetfmablog`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_commentaire_post1`
    FOREIGN KEY (`FK_post_id`)
    REFERENCES `projetfmablog`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_commentaire_statut_commentaire1`
    FOREIGN KEY (`FK_statut_comment_id`)
    REFERENCES `projetfmablog`.`comment_statut` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

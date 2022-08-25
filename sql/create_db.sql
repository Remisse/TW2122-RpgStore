-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


-- -----------------------------------------------------
-- Schema rpgstore
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `rpgstore` DEFAULT CHARACTER SET utf8mb4 ;
USE `rpgstore` ;


-- -----------------------------------------------------
-- Table `rpgstore`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`user` (
  `userid` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(16) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`userid`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`client` (
  `user` INT NOT NULL,
  `billingaddress` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`user`),
  INDEX `fk_client_is_user_idx` (`user` ASC),
  CONSTRAINT `fk_client_is_user`
    FOREIGN KEY (`user`)
    REFERENCES `rpgstore`.`user` (`userid`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`admin` (
  `user` INT NOT NULL,
  PRIMARY KEY (`user`),
  INDEX `fk_admin_is_user_idx` (`user` ASC),
  CONSTRAINT `fk_admin_is_user`
    FOREIGN KEY (`user`)
    REFERENCES `rpgstore`.`user` (`userid`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`brand`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`brand` (
  `brandid` INT NOT NULL AUTO_INCREMENT,
  `brandname` VARCHAR(50) NOT NULL,
  `brandshortname` VARCHAR(50) NOT NULL,
  `brandpopularity` INT NOT NULL,
  `brandcoverimg` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`brandid`),
  FULLTEXT (`brandname`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`item` (
  `itemid` INT NOT NULL AUTO_INCREMENT,
  `itemname` VARCHAR(100) NOT NULL,
  `itemdescription` MEDIUMTEXT NOT NULL,
  `iteminsertiondate` DATE NOT NULL,
  `itemimg` VARCHAR(100) NOT NULL,
  `itemprice` BIGINT NOT NULL,
  `itemdiscount` DECIMAL (12,8) NOT NULL DEFAULT 0.0,
  `itemstock` INT NOT NULL,
  `itembrand` INT,
  `itemcreator` VARCHAR(100) NOT NULL,
  `itempublisher` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`itemid`),
  FULLTEXT (`itemname`),
  CONSTRAINT `fk_item_part_of_brand`
    FOREIGN KEY (`itembrand`)
    REFERENCES `rpgstore`.`brand` (`brandid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`order` (
    `orderid` INT NOT NULL AUTO_INCREMENT,
    `user` INT NOT NULL,
    `creationdate` DATE NOT NULL,
    `paymentdate` DATE,
    PRIMARY KEY (`orderid`),
    INDEX `fk_order_has_user` (`user` ASC),
    CONSTRAINT `fk_order_has_user`
        FOREIGN KEY (`user`)
        REFERENCES `rpgstore`.`user` (`userid`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`category` (
  `categoryid` INT NOT NULL AUTO_INCREMENT,
  `categoryname` VARCHAR(50) NOT NULL,
  `categorysuper` INT,
  PRIMARY KEY (`categoryid`),
  FULLTEXT (`categoryname`),
  INDEX `fk_category_has_supercategory_idx` (`categorysuper` ASC),
  CONSTRAINT `fk_category_has_supercategory`
    FOREIGN KEY (`categorysuper`)
    REFERENCES `rpgstore`.`category` (`categoryid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`item_has_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`item_has_category` (
  `item` INT NOT NULL,
  `category` INT NOT NULL,
  PRIMARY KEY (`item`, `category`),
  INDEX `fk_item_has_category_category1_idx` (`category` ASC),
  INDEX `fk_item_has_category_item1_idx` (`item` ASC),
  CONSTRAINT `fk_item_has_category_item1`
    FOREIGN KEY (`item`)
    REFERENCES `rpgstore`.`item` (`itemid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_has_category_category1`
    FOREIGN KEY (`category`)
    REFERENCES `rpgstore`.`category` (`categoryid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`order_has_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`order_has_item` (
    `order` INT NOT NULL,
    `item` INT NOT NULL,
    `qty` INT NOT NULL,
    `unitprice` BIGINT NOT NULL,
    PRIMARY KEY (`order`, `item`),
    INDEX `fk_order_has_item_1_idx` (`item` ASC),
    INDEX `fk_order_has_item_2_idx` (`order` ASC),
    CONSTRAINT `fk_order_has_item_1`
        FOREIGN KEY (`item`)
        REFERENCES `rpgstore`.`item` (`itemid`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_order_has_item_2`
        FOREIGN KEY (`order`)
        REFERENCES `rpgstore`.`order` (`orderid`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rpgstore`.`ordernotification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rpgstore`.`ordernotification` (
    `notificationid` INT NOT NULL AUTO_INCREMENT,
    `user` INT NOT NULL,
    `order` INT NOT NULL,
    `message` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`notificationid`),
        CONSTRAINT `fk_notification_has_order`
        FOREIGN KEY (`order`)
        REFERENCES `rpgstore`.`order` (`orderid`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_notification_has_user`
        FOREIGN KEY (`user`)
        REFERENCES `rpgstore`.`user` (`userid`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `device-log` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `device-log` ;

-- -----------------------------------------------------
-- Table `device-log`.`person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`person` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Person\'s code, as found on their identification',
  `code` VARCHAR(64) NOT NULL COMMENT 'Identification code, as scanned from ID card',
  `is_staff` INT(1) NOT NULL DEFAULT 0 COMMENT '1 for staff, 0 for non-staff',
  `is_active` INT(1) NOT NULL DEFAULT 1 COMMENT '0 for inactive people, 1 for active',
  `firstname` VARCHAR(64) NOT NULL COMMENT 'Person\'s given name',
  `surname` VARCHAR(64) NOT NULL COMMENT 'Person\'s surname',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `person_code` (`code` ASC))
ENGINE = InnoDB
COMMENT = 'Person who has keys, devices and software';


-- -----------------------------------------------------
-- Table `device-log`.`device_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`device_status` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Number for this status',
  `tag` VARCHAR(45) NOT NULL COMMENT 'Human-readable status code',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `tag_UNIQUE` (`tag` ASC))
ENGINE = InnoDB
COMMENT = 'List of device statuses';


-- -----------------------------------------------------
-- Table `device-log`.`device_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`device_type` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for this device type',
  `name` VARCHAR(45) NOT NULL COMMENT 'Human-readable device family description',
  `model_no` VARCHAR(45) NOT NULL COMMENT 'Manufacturer model number',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'List of device types';


-- -----------------------------------------------------
-- Table `device-log`.`device`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`device` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of the device',
  `is_spare` INT(1) NOT NULL COMMENT '1 if the device is spare, 0 otherwise',
  `is_damaged` INT(1) NOT NULL COMMENT '1 if the device is damaged, 0 otherwise',
  `sn` VARCHAR(45) NOT NULL COMMENT 'Manufacturer-assigned device serial number',
  `mac_eth0` VARCHAR(17) NOT NULL COMMENT 'Ethernet MAC address',
  `mac_wlan0` VARCHAR(17) NOT NULL COMMENT 'Wireless MAC address',
  `is_bought` INT(1) NOT NULL COMMENT '1 if the device is bought out, 0 for organisation-owned',
  `person_id` INT NOT NULL COMMENT 'ID of person currently responsible for device',
  `device_status_id` INT NOT NULL COMMENT 'Current device status',
  `device_type_id` INT NOT NULL COMMENT 'ID for this model of device',
  PRIMARY KEY (`id`),
  INDEX `person_id` (`person_id` ASC),
  INDEX `device_status_id` (`device_status_id` ASC),
  INDEX `device_type_id` (`device_type_id` ASC),
  CONSTRAINT `fk_device_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `device-log`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_device_device_status1`
    FOREIGN KEY (`device_status_id`)
    REFERENCES `device-log`.`device_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_device_device_type1`
    FOREIGN KEY (`device_type_id`)
    REFERENCES `device-log`.`device_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Individual devices being tracked';


-- -----------------------------------------------------
-- Table `device-log`.`software_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`software_type` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for the software type',
  `name` VARCHAR(45) NOT NULL COMMENT 'Name of the software intallation',
  UNIQUE INDEX `name` (`name` ASC),
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'List of software types';


-- -----------------------------------------------------
-- Table `device-log`.`software_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`software_status` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for the software status',
  `tag` VARCHAR(45) NOT NULL COMMENT 'Human-readable status tag',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `tag` (`tag` ASC))
ENGINE = InnoDB
COMMENT = 'List of software installation statuses';


-- -----------------------------------------------------
-- Table `device-log`.`software`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`software` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for this software installation',
  `code` VARCHAR(128) NOT NULL COMMENT 'Vendor-issued code or serial number associated with this installation',
  `software_type_id` INT NOT NULL COMMENT 'Type of software which has been installed',
  `software_status_id` INT NOT NULL COMMENT 'Current status code for the software',
  `person_id` INT NOT NULL COMMENT 'Person with this software installation',
  `is_bought` INT(1) NOT NULL COMMENT '1 for bought out software, 0 for organisation-owned',
  PRIMARY KEY (`id`),
  INDEX `software_type_id` (`software_type_id` ASC),
  INDEX `software_status_id` (`software_status_id` ASC),
  INDEX `software_person_id` (`person_id` ASC),
  CONSTRAINT `fk_software_software_type1`
    FOREIGN KEY (`software_type_id`)
    REFERENCES `device-log`.`software_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_software_software_status1`
    FOREIGN KEY (`software_status_id`)
    REFERENCES `device-log`.`software_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_software_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `device-log`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Software installations being tracked';


-- -----------------------------------------------------
-- Table `device-log`.`key_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`key_type` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for this type of key',
  `name` VARCHAR(45) NOT NULL COMMENT 'Human readable name of this key type',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Types of keys';


-- -----------------------------------------------------
-- Table `device-log`.`technician`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`technician` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Internal technician ID',
  `login` VARCHAR(45) NOT NULL COMMENT 'Login to use for authentication',
  `name` VARCHAR(45) NOT NULL COMMENT 'Name for display purposes',
  `is_active` INT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `technician_name` (`name` ASC),
  UNIQUE INDEX `technician_login` (`login` ASC))
ENGINE = InnoDB
COMMENT = 'Technicians who add entries';


-- -----------------------------------------------------
-- Table `device-log`.`software_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`software_history` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for this history entry',
  `date` DATETIME NOT NULL COMMENT 'Date of the entry',
  `person_id` INT NOT NULL COMMENT 'ID of person who has the software at this stage',
  `software_id` INT NOT NULL COMMENT 'ID of the associated software installation',
  `technician_id` INT NOT NULL COMMENT 'Technician who added the entry',
  `software_status_id` INT NOT NULL COMMENT 'Status code aplicable to the installation at this time',
  `comment` TEXT NOT NULL COMMENT 'Technician comment',
  `change` ENUM('comment','status','bought') NOT NULL COMMENT 'Field which was affected by this history entry',
  `is_bought` INT(1) NOT NULL COMMENT '1 if the software was bought-out at this point in time',
  PRIMARY KEY (`id`),
  INDEX `person_id` (`person_id` ASC),
  INDEX `software_id` (`software_id` ASC),
  INDEX `technician_id` (`technician_id` ASC),
  INDEX `software_status_id` (`software_status_id` ASC),
  CONSTRAINT `fk_software_history_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `device-log`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_software_history_software1`
    FOREIGN KEY (`software_id`)
    REFERENCES `device-log`.`software` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_software_history_technician1`
    FOREIGN KEY (`technician_id`)
    REFERENCES `device-log`.`technician` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_software_history_software_status1`
    FOREIGN KEY (`software_status_id`)
    REFERENCES `device-log`.`software_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'History of a software installation';


-- -----------------------------------------------------
-- Table `device-log`.`key_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`key_status` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for the status',
  `name` VARCHAR(45) NOT NULL COMMENT 'Human-readable status code',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Lost of key statuses';


-- -----------------------------------------------------
-- Table `device-log`.`doorkey`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`doorkey` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID for this key',
  `serial` VARCHAR(128) NOT NULL COMMENT 'Serial number appearing on the key',
  `person_id` INT NOT NULL COMMENT 'Person who currently has the key',
  `is_spare` INT(1) NOT NULL COMMENT '1 if the key is \'spare\', 0 if it is not',
  `key_type_id` INT NOT NULL COMMENT 'ID of the type of key',
  `key_status_id` INT NOT NULL COMMENT 'Current leu status',
  PRIMARY KEY (`id`),
  INDEX `person_id` (`person_id` ASC),
  INDEX `key_type_id` (`key_type_id` ASC),
  INDEX `key_status_id` (`key_status_id` ASC),
  CONSTRAINT `fk_key_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `device-log`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_key_key_type1`
    FOREIGN KEY (`key_type_id`)
    REFERENCES `device-log`.`key_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_key_key_status1`
    FOREIGN KEY (`key_status_id`)
    REFERENCES `device-log`.`key_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Phsical keys issued to people';


-- -----------------------------------------------------
-- Table `device-log`.`key_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`key_history` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of the key history entry',
  `date` DATETIME NOT NULL COMMENT 'Date that this log entry was added',
  `person_id` INT NOT NULL COMMENT 'ID of person who had the key at this point in time',
  `key_id` INT NOT NULL COMMENT 'ID of the associated key',
  `technician_id` INT NOT NULL COMMENT 'Technician who added this entry',
  `key_status_id` INT NOT NULL COMMENT 'Status code for the key',
  `comment` VARCHAR(45) NOT NULL COMMENT 'Technician comment',
  `change` ENUM('status','comment') NOT NULL COMMENT 'field which was changed by this entry',
  `is_spare` INT(1) NOT NULL COMMENT '1 if the key is currently spare, 0 otherwise',
  PRIMARY KEY (`id`),
  INDEX `person_id` (`person_id` ASC),
  INDEX `key_id` (`key_id` ASC),
  INDEX `technician_id` (`technician_id` ASC),
  INDEX `key_status_id` (`key_status_id` ASC),
  CONSTRAINT `fk_key_history_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `device-log`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_key_history_key1`
    FOREIGN KEY (`key_id`)
    REFERENCES `device-log`.`doorkey` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_key_history_technician1`
    FOREIGN KEY (`technician_id`)
    REFERENCES `device-log`.`technician` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_key_history_key_status1`
    FOREIGN KEY (`key_status_id`)
    REFERENCES `device-log`.`key_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'History of key ownership';


-- -----------------------------------------------------
-- Table `device-log`.`device_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`device_history` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of device history entry',
  `date` DATETIME NOT NULL COMMENT 'Time of this log entry',
  `comment` TEXT NOT NULL COMMENT 'Technician comment',
  `is_spare` INT(1) NOT NULL COMMENT '1 if device is a \'spare\', 0 if not',
  `is_damaged` INT(1) NOT NULL COMMENT '1 if device is damaged, 0 otherwise',
  `has_photos` INT(1) NOT NULL COMMENT '1 if photos have been uploaded, 0 otherwise',
  `is_bought` INT(1) NOT NULL COMMENT '1 for bought-out device, 0 for organisation-owned',
  `change` ENUM('comment','photo','owner','status','damaged','spare','bought') NOT NULL COMMENT 'Field which was changed with this log entry',
  `technician_id` INT NOT NULL COMMENT 'Technician who uploaded the entry\n',
  `device_id` INT NOT NULL COMMENT 'Device being referred to',
  `device_status_id` INT NOT NULL COMMENT 'Status code fot the device',
  `person_id` INT NOT NULL COMMENT 'ID of device holder at this point in time\n',
  PRIMARY KEY (`id`),
  INDEX `technician_id` (`technician_id` ASC),
  INDEX `device_id` (`device_id` ASC),
  INDEX `device_status_id` (`device_status_id` ASC),
  INDEX `person_id` (`person_id` ASC),
  CONSTRAINT `fk_device_history_technician1`
    FOREIGN KEY (`technician_id`)
    REFERENCES `device-log`.`technician` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_device_history_device1`
    FOREIGN KEY (`device_id`)
    REFERENCES `device-log`.`device` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_device_history_device_status1`
    FOREIGN KEY (`device_status_id`)
    REFERENCES `device-log`.`device_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_device_history_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `device-log`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'History of an individual device';


-- -----------------------------------------------------
-- Table `device-log`.`device_photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `device-log`.`device_photo` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'ID of the photo',
  `checksum` CHAR(64) NOT NULL COMMENT 'ASCII rendering of SHA-256 hash of the file, for storage',
  `filename` TEXT NOT NULL COMMENT 'Filename at upload, used for labelling only',
  `device_history_id` INT NOT NULL COMMENT 'Associated log entry',
  PRIMARY KEY (`id`),
  INDEX `device_history_id` (`device_history_id` ASC),
  CONSTRAINT `fk_device_photo_device_history1`
    FOREIGN KEY (`device_history_id`)
    REFERENCES `device-log`.`device_history` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Photos added to an entry';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

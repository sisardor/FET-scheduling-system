SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users` (
  `user_name` VARCHAR(10) NOT NULL ,
  `password` CHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL ,
  PRIMARY KEY (`user_name`) ,
  UNIQUE INDEX `user_name_UNIQUE` (`user_name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_tables`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_tables` (
  `user_table_id` INT NOT NULL AUTO_INCREMENT ,
  `table_name` VARCHAR(45) NULL ,
  `institution_name` VARCHAR(45) NULL ,
  `comments` LONGTEXT NULL ,
  `user_name` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`user_table_id`) ,
  INDEX `fk_user_tables_users1` (`user_name` ASC) ,
  CONSTRAINT `fk_user_tables_users1`
    FOREIGN KEY (`user_name` )
    REFERENCES `users` (`user_name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_files`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_files` (
  `user_files_id` INT NOT NULL AUTO_INCREMENT ,
  `file_type` VARCHAR(45) NULL ,
  `file size` VARCHAR(45) NULL ,
  `file_content` TEXT NULL ,
  `file_name` VARCHAR(45) NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`user_files_id`) ,
  INDEX `fk_user_tables` (`user_table_id` ASC) ,
  CONSTRAINT `fk_user_files_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `semesters`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `semesters` (
  `sem_id` INT NOT NULL AUTO_INCREMENT ,
  `user_table_id` INT NOT NULL ,
  `sem_name` VARCHAR(10) NULL ,
  PRIMARY KEY (`sem_id`) ,
  INDEX `fk_semesters_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_semesters_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teachers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `teachers` (
  `teacher_id` INT NOT NULL AUTO_INCREMENT ,
  `teach_name` VARCHAR(45) NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`teacher_id`) ,
  INDEX `fk_teachers_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_teachers_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `students`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `students` (
  `student_id` INT NOT NULL AUTO_INCREMENT ,
  `year_name` VARCHAR(45) NULL ,
  `num_students` INT NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`student_id`) ,
  INDEX `fk_students_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_students_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `subjects`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `subjects` (
  `subj_id` INT NOT NULL AUTO_INCREMENT ,
  `subj_name` VARCHAR(45) NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`subj_id`) ,
  INDEX `fk_subjects_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_subjects_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `activities`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `activities` (
  `activities_id` INT NOT NULL AUTO_INCREMENT ,
  `duration` VARCHAR(45) NULL ,
  `total_duration` VARCHAR(45) NULL ,
  `active` TINYINT NULL ,
  `teacher_id` INT NOT NULL ,
  `subj_id` INT NOT NULL ,
  `student_id` INT NOT NULL ,
  `user_table_id` INT NOT NULL ,
  `activity_group_id` INT NULL ,
  PRIMARY KEY (`activities_id`) ,
  INDEX `fk_actvites_teachers1` (`teacher_id` ASC) ,
  INDEX `fk_actvites_subjects1` (`subj_id` ASC) ,
  INDEX `fk_actvites_students1` (`student_id` ASC) ,
  INDEX `fk_activities_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_actvites_teachers1`
    FOREIGN KEY (`teacher_id` )
    REFERENCES `teachers` (`teacher_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_actvites_subjects1`
    FOREIGN KEY (`subj_id` )
    REFERENCES `subjects` (`subj_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_actvites_students1`
    FOREIGN KEY (`student_id` )
    REFERENCES `students` (`student_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_activities_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `buildings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `buildings` (
  `building_id` INT NOT NULL AUTO_INCREMENT ,
  `build_name` VARCHAR(45) NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`building_id`) ,
  INDEX `fk_buildings_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_buildings_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rooms`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `rooms` (
  `room_id` INT NOT NULL AUTO_INCREMENT ,
  `room_name` VARCHAR(10) NULL ,
  `capacity` INT NULL ,
  `building_id` INT NOT NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`room_id`) ,
  INDEX `fk_rooms_building1` (`building_id` ASC) ,
  INDEX `fk_rooms_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_rooms_building1`
    FOREIGN KEY (`building_id` )
    REFERENCES `buildings` (`building_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rooms_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `constraints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `constraints` (
  `cons_id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(6) NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`cons_id`) ,
  INDEX `fk_constriants_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_constriants_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `time_constraints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `time_constraints` (
  `time_cons_id` INT NOT NULL AUTO_INCREMENT ,
  `cons_id` INT NOT NULL ,
  `type` VARCHAR(45) NULL COMMENT '(min days or same start)\n' ,
  PRIMARY KEY (`time_cons_id`) ,
  INDEX `fk_time_cons_constriants1` (`cons_id` ASC) ,
  CONSTRAINT `fk_time_cons_constriants1`
    FOREIGN KEY (`cons_id` )
    REFERENCES `constraints` (`cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `min_days_constraints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `min_days_constraints` (
  `min_days_cons_id` INT NOT NULL AUTO_INCREMENT ,
  `weight_percentage` VARCHAR(5) NULL ,
  `consecutive_if_same_day` TINYINT NULL ,
  `num_of_activites` INT NULL ,
  `min_days` VARCHAR(45) NULL ,
  `active` VARCHAR(5) NULL ,
  `comments` LONGTEXT NULL ,
  `time_cons_id` INT NOT NULL ,
  PRIMARY KEY (`min_days_cons_id`) ,
  INDEX `fk_min_days_constraint_time_constraints_for_activites1` (`time_cons_id` ASC) ,
  CONSTRAINT `fk_min_days_constraint_time_constraints_for_activites1`
    FOREIGN KEY (`time_cons_id` )
    REFERENCES `time_constraints` (`time_cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `min_days_for_activities`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `min_days_for_activities` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `min_days_cons_id` INT NOT NULL ,
  `activities_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_min_days_for_activities_min_days_constraint1` (`min_days_cons_id` ASC) ,
  INDEX `fk_min_days_for_activities_actvites1` (`activities_id` ASC) ,
  CONSTRAINT `fk_min_days_for_activities_min_days_constraint1`
    FOREIGN KEY (`min_days_cons_id` )
    REFERENCES `min_days_constraints` (`min_days_cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_min_days_for_activities_actvites1`
    FOREIGN KEY (`activities_id` )
    REFERENCES `activities` (`activities_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `basic_compulsory_constraints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `basic_compulsory_constraints` (
  `basic_cons_id` INT NOT NULL AUTO_INCREMENT ,
  `weight_percentage` VARCHAR(5) NULL ,
  `active` VARCHAR(5) NULL ,
  `comments` LONGTEXT NULL ,
  `cons_id` INT NOT NULL ,
  PRIMARY KEY (`basic_cons_id`) ,
  INDEX `fk_basic_compulsory_constraint_constriants1` (`cons_id` ASC) ,
  CONSTRAINT `fk_basic_compulsory_constraint_constriants1`
    FOREIGN KEY (`cons_id` )
    REFERENCES `constraints` (`cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `same_start_hr_constraints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `same_start_hr_constraints` (
  `same_start_cons_id` INT NOT NULL ,
  `weight_percentage` VARCHAR(5) NULL ,
  `num_of_activites` VARCHAR(45) NULL ,
  `active` VARCHAR(5) NULL ,
  `comments` VARCHAR(45) NULL ,
  `time_cons_id` INT NOT NULL ,
  PRIMARY KEY (`same_start_cons_id`) ,
  INDEX `fk_same_start_hr_constraint_time_constraints_for_activites1` (`time_cons_id` ASC) ,
  CONSTRAINT `fk_same_start_hr_constraint_time_constraints_for_activites1`
    FOREIGN KEY (`time_cons_id` )
    REFERENCES `time_constraints` (`time_cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `activities_same_start`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `activities_same_start` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `same_start_cons_id` INT NOT NULL ,
  `actvites_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_activites_same_start_same_start_hr_constraint1` (`same_start_cons_id` ASC) ,
  INDEX `fk_activites_same_start_actvites1` (`actvites_id` ASC) ,
  CONSTRAINT `fk_activites_same_start_same_start_hr_constraint1`
    FOREIGN KEY (`same_start_cons_id` )
    REFERENCES `same_start_hr_constraints` (`same_start_cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_activites_same_start_actvites1`
    FOREIGN KEY (`actvites_id` )
    REFERENCES `activities` (`activities_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `space_constraints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `space_constraints` (
  `space_cons_id` INT NOT NULL AUTO_INCREMENT ,
  `weight_percentage` VARCHAR(45) NULL ,
  `num_of_pref_rooms` INT NULL ,
  `active` VARCHAR(5) NULL ,
  `comments` LONGTEXT NULL ,
  `cons_id` INT NOT NULL ,
  PRIMARY KEY (`space_cons_id`) ,
  INDEX `fk_space_constraints_constriants1` (`cons_id` ASC) ,
  CONSTRAINT `fk_space_constraints_constriants1`
    FOREIGN KEY (`cons_id` )
    REFERENCES `constraints` (`cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `preferred_rooms`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `preferred_rooms` (
  `pref_rooms_id` INT NOT NULL AUTO_INCREMENT ,
  `space_cons_id` INT NOT NULL ,
  `room_id` INT NOT NULL ,
  PRIMARY KEY (`pref_rooms_id`) ,
  INDEX `fk_preferred_rooms_space_constraints1` (`space_cons_id` ASC) ,
  INDEX `fk_preferred_rooms_rooms1` (`room_id` ASC) ,
  CONSTRAINT `fk_preferred_rooms_space_constraints1`
    FOREIGN KEY (`space_cons_id` )
    REFERENCES `space_constraints` (`space_cons_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_preferred_rooms_rooms1`
    FOREIGN KEY (`room_id` )
    REFERENCES `rooms` (`room_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hours`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hours` (
  `hours_id` INT NOT NULL AUTO_INCREMENT ,
  `hour_name` VARCHAR(45) NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`hours_id`) ,
  INDEX `fk_hours_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_hours_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `days`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `days` (
  `days_id` INT NOT NULL AUTO_INCREMENT ,
  `day_name` VARCHAR(45) NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`days_id`) ,
  INDEX `fk_days_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_days_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `groups` (
  `group_id` INT NOT NULL AUTO_INCREMENT ,
  `year_name` VARCHAR(45) NULL ,
  `num_of_students` VARCHAR(45) NULL ,
  `student_id` INT NOT NULL ,
  `user_table_id` INT NOT NULL ,
  PRIMARY KEY (`group_id`) ,
  INDEX `fk_group_students1` (`student_id` ASC) ,
  INDEX `fk_group_user_tables1` (`user_table_id` ASC) ,
  CONSTRAINT `fk_group_students1`
    FOREIGN KEY (`student_id` )
    REFERENCES `students` (`student_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_user_tables1`
    FOREIGN KEY (`user_table_id` )
    REFERENCES `user_tables` (`user_table_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- THIS IS DATA SECTION
LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activities_same_start`
--

LOCK TABLES `activities_same_start` WRITE;
/*!40000 ALTER TABLE `activities_same_start` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities_same_start` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `basic_compulsory_constraints`
--

LOCK TABLES `basic_compulsory_constraints` WRITE;
/*!40000 ALTER TABLE `basic_compulsory_constraints` DISABLE KEYS */;
/*!40000 ALTER TABLE `basic_compulsory_constraints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `buildings`
--

LOCK TABLES `buildings` WRITE;
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` VALUES (1,'A',1),(8,'B',1),(11,'A',1);
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `constraints`
--

LOCK TABLES `constraints` WRITE;
/*!40000 ALTER TABLE `constraints` DISABLE KEYS */;
/*!40000 ALTER TABLE `constraints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `days`
--

LOCK TABLES `days` WRITE;
/*!40000 ALTER TABLE `days` DISABLE KEYS */;
/*!40000 ALTER TABLE `days` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `hours`
--

LOCK TABLES `hours` WRITE;
/*!40000 ALTER TABLE `hours` DISABLE KEYS */;
/*!40000 ALTER TABLE `hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `min_days_constraints`
--

LOCK TABLES `min_days_constraints` WRITE;
/*!40000 ALTER TABLE `min_days_constraints` DISABLE KEYS */;
/*!40000 ALTER TABLE `min_days_constraints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `min_days_for_activities`
--

LOCK TABLES `min_days_for_activities` WRITE;
/*!40000 ALTER TABLE `min_days_for_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `min_days_for_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `preferred_rooms`
--

LOCK TABLES `preferred_rooms` WRITE;
/*!40000 ALTER TABLE `preferred_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `preferred_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (2,'A23',11,1,1),(4,'A201',0,1,1),(8,'B123',45,8,1),(9,'B50',50,8,1);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `same_start_hr_constraints`
--

LOCK TABLES `same_start_hr_constraints` WRITE;
/*!40000 ALTER TABLE `same_start_hr_constraints` DISABLE KEYS */;
/*!40000 ALTER TABLE `same_start_hr_constraints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `semesters`
--

LOCK TABLES `semesters` WRITE;
/*!40000 ALTER TABLE `semesters` DISABLE KEYS */;
INSERT INTO `semesters` VALUES (1,1,'Fall2013');
/*!40000 ALTER TABLE `semesters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `space_constraints`
--

LOCK TABLES `space_constraints` WRITE;
/*!40000 ALTER TABLE `space_constraints` DISABLE KEYS */;
/*!40000 ALTER TABLE `space_constraints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (2,'Q',12,1),(3,'A',50,1);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (2,'Math1223',1),(3,'ENGL1234',1);
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (2,'Janny, L',1),(3,'Micheal',1),(4,'John, K.',1);
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `time_constraints`
--

LOCK TABLES `time_constraints` WRITE;
/*!40000 ALTER TABLE `time_constraints` DISABLE KEYS */;
/*!40000 ALTER TABLE `time_constraints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_files`
--

LOCK TABLES `user_files` WRITE;
/*!40000 ALTER TABLE `user_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_tables`
--

LOCK TABLES `user_tables` WRITE;
/*!40000 ALTER TABLE `user_tables` DISABLE KEYS */;
INSERT INTO `user_tables` VALUES (1,'Langara.fet','Langara',NULL,'admin'),(2,'Langara.fet','Langara',NULL,'sar'),(6,NULL,'test',NULL,'jas'),(10,NULL,'test2',NULL,'jas'),(11,NULL,'test2',NULL,'jas'),(12,NULL,'Keberly',NULL,'jas'),(13,NULL,'qw',NULL,'jas'),(14,NULL,'1234',NULL,'jas');
/*!40000 ALTER TABLE `user_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','81dc9bdb52d04dc20036dbd8313ed055'),('jas','c70903749ed556d98a4966fdfb9ccd04'),('sar','74101aa14a1d883badc954c4f462e365'),('user','5ebe2294ecd0e0f08eab7690d2a6ee69');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

/*================================================================================*/
/* DDL SCRIPT                                                                     */
/*================================================================================*/
/*  Title    : ABC Framework                                                      */
/*  FileName : abc-core.ecm                                                       */
/*  Platform : MySQL 5                                                            */
/*  Version  : Concept                                                            */
/*  Date     : zaterdag 25 november 2017                                          */
/*================================================================================*/
/*================================================================================*/
/* CREATE TABLES                                                                  */
/*================================================================================*/

CREATE TABLE `AUT_CONFIG_CLASS` (
  `ccl_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `ccl_type` VARCHAR(10) NOT NULL,
  `ccl_class` VARCHAR(128) NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`ccl_id`)
)
engine=innodb;

/*
COMMENT ON COLUMN `AUT_CONFIG_CLASS`.`ccl_type`
The PHP type of the parameter value.
*/

/*
COMMENT ON COLUMN `AUT_CONFIG_CLASS`.`ccl_class`
The class for showing and modifying the parameter value.
*/

CREATE TABLE `AUT_CONFIG` (
  `cfg_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `ccl_id` SMALLINT UNSIGNED NOT NULL,
  `cfg_mandatory` BOOL DEFAULT 1 NOT NULL,
  `cfg_show_to_company` BOOL DEFAULT 0 NOT NULL,
  `cfg_modify_by_company` BOOL DEFAULT 0 NOT NULL,
  `cfg_description` VARCHAR(400) NOT NULL,
  `cfg_label` VARCHAR(50) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`cfg_id`)
)
engine=innodb;

/*
COMMENT ON COLUMN `AUT_CONFIG`.`cfg_mandatory`
If true: this parameter must have a value.
*/

/*
COMMENT ON COLUMN `AUT_CONFIG`.`cfg_show_to_company`
If true: this parameter is visible by the administrator of the company. Otherwise this parameter is visible to the system administrator.
*/

/*
COMMENT ON COLUMN `AUT_CONFIG`.`cfg_modify_by_company`
If true: the value of this parameter can be modified by the administrator of the company.
*/

CREATE TABLE `AUT_CONFIG_VALUE` (
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `cfg_id` SMALLINT UNSIGNED NOT NULL,
  `cfg_value` VARCHAR(4000),
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`cmp_id`, `cfg_id`)
)
engine=innodb;

CREATE TABLE `AUT_FLAG` (
  `rfl_id` TINYINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `rfl_flag` INT UNSIGNED NOT NULL,
  `rfl_name` VARCHAR(80) NOT NULL,
  `rfl_function` VARCHAR(3) DEFAULT 'OR' NOT NULL,
  CONSTRAINT `PK_AUT_FLAG` PRIMARY KEY (`rfl_id`)
);

/*
COMMENT ON COLUMN `AUT_FLAG`.`rfl_flag`
The flag (only a single bit can be set).
*/

/*
COMMENT ON COLUMN `AUT_FLAG`.`rfl_name`
The description of this flag.
*/

/*
COMMENT ON COLUMN `AUT_FLAG`.`rfl_function`
The bitwise function for aggregating this flag.
*/

CREATE TABLE `AUT_MODULE` (
  `mdl_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `wrd_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`mdl_id`)
)
engine=innodb;

CREATE TABLE `AUT_FUNCTIONALITY` (
  `fun_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `mdl_id` SMALLINT UNSIGNED NOT NULL,
  `wrd_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`fun_id`)
)
engine=innodb;

CREATE TABLE `AUT_PAGE_TAB` (
  `ptb_id` TINYINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `wrd_id` SMALLINT UNSIGNED NOT NULL,
  `ptb_label` VARCHAR(30) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`ptb_id`)
)
engine=innodb;

CREATE TABLE `AUT_PAGE` (
  `pag_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `pag_id_org` SMALLINT UNSIGNED,
  `ptb_id` TINYINT UNSIGNED,
  `mnu_id` SMALLINT UNSIGNED,
  `wrd_id` SMALLINT UNSIGNED NOT NULL,
  `pag_alias` VARCHAR(32),
  `pag_class` VARCHAR(128) NOT NULL,
  `pag_label` VARCHAR(128) CHARACTER SET ascii COLLATE ascii_general_ci,
  `pag_weight` INT,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`pag_id`)
);

/*
COMMENT ON COLUMN `AUT_PAGE`.`pag_alias`
The URL alias of this page.
*/

/*
COMMENT ON COLUMN `AUT_PAGE`.`pag_class`
The PHP class that generates this page.
*/

/*
COMMENT ON COLUMN `AUT_PAGE`.`pag_label`
The PHP constant name of this page.
*/

/*
COMMENT ON COLUMN `AUT_PAGE`.`pag_weight`
The weight for sorting.
*/

CREATE TABLE `AUT_MENU` (
  `mnu_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `wrd_id` SMALLINT UNSIGNED NOT NULL,
  `pag_id` SMALLINT UNSIGNED NOT NULL,
  `mnu_level` TINYINT NOT NULL,
  `mnu_group` SMALLINT NOT NULL,
  `mnu_weight` SMALLINT NOT NULL,
  `mnu_link` VARCHAR(64),
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`mnu_id`)
);

CREATE TABLE `AUT_MODULE_COMPANY` (
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `mdl_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`cmp_id`, `mdl_id`),
  CONSTRAINT `SECONDARY` UNIQUE (`mdl_id`, `cmp_id`)
)
engine=innodb;

CREATE TABLE `AUT_PAG_FUN` (
  `pag_id` SMALLINT UNSIGNED NOT NULL,
  `fun_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`fun_id`, `pag_id`),
  CONSTRAINT `SECONDARY` UNIQUE (`pag_id`, `fun_id`)
)
engine=innodb;

CREATE TABLE `AUT_PAGE_COMPANY` (
  `pag_id` SMALLINT UNSIGNED NOT NULL,
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `pag_class` VARCHAR(128) NOT NULL,
  CONSTRAINT `PK_AUT_PAGE_COMPANY` PRIMARY KEY (`pag_id`, `cmp_id`)
)
engine=innodb;

CREATE TABLE `AUT_PRO_PAG` (
  `pag_id` SMALLINT UNSIGNED NOT NULL,
  `pro_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PK_AUT_PRO_PAG` PRIMARY KEY (`pro_id`, `pag_id`)
);

CREATE TABLE `AUT_ROLE_GROUP` (
  `rlg_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `wrd_id` SMALLINT UNSIGNED NOT NULL,
  `rlg_weight` SMALLINT UNSIGNED NOT NULL,
  `rlg_label` VARCHAR(50) CHARACTER SET ascii COLLATE ascii_general_ci,
  CONSTRAINT `PK_AUT_ROLE_GROUP` PRIMARY KEY (`rlg_id`)
);

/*
COMMENT ON COLUMN `AUT_ROLE_GROUP`.`wrd_id`
The name of the role group.
*/

/*
COMMENT ON COLUMN `AUT_ROLE_GROUP`.`rlg_weight`
The weight of the role group for sorting.
*/

CREATE TABLE `AUT_ROLE` (
  `rol_id` SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `rlg_id` SMALLINT UNSIGNED NOT NULL,
  `rol_weight` SMALLINT NOT NULL,
  `rol_name` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rol_label` VARCHAR(50) CHARACTER SET ascii COLLATE ascii_general_ci,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`rol_id`)
);

/*
COMMENT ON COLUMN `AUT_ROLE`.`rol_weight`
The weight for sorting.
*/

/*
COMMENT ON COLUMN `AUT_ROLE`.`rol_name`
The name or description of this role.
*/

CREATE TABLE `AUT_PRO_ROL` (
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `pro_id` SMALLINT UNSIGNED NOT NULL,
  `rol_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PK_AUT_PRO_ROL` PRIMARY KEY (`pro_id`, `rol_id`)
);

CREATE TABLE `AUT_ROL_FLG` (
  `rfl_id` TINYINT UNSIGNED NOT NULL,
  `rol_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PK_AUT_ROL_FLG` PRIMARY KEY (`rfl_id`, `rol_id`)
);

CREATE TABLE `AUT_ROL_FUN` (
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `fun_id` SMALLINT UNSIGNED NOT NULL,
  `rol_id` SMALLINT UNSIGNED NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`rol_id`, `fun_id`),
  CONSTRAINT `SECONDARY` UNIQUE (`fun_id`, `rol_id`)
)
engine=innodb;

CREATE TABLE `AUT_USR_ROL` (
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `usr_id` INTEGER UNSIGNED NOT NULL,
  `rol_id` SMALLINT UNSIGNED NOT NULL,
  `aur_date_start` DATE NOT NULL,
  `aur_date_stop` DATE NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`usr_id`, `rol_id`),
  CONSTRAINT `SECONDAY` UNIQUE (`rol_id`, `usr_id`)
);

CREATE TABLE `LOG_EVENT_TYPE` (
  `let_id` TINYINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `wrd_id` SMALLINT UNSIGNED NOT NULL,
  `let_label` VARCHAR(50) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`let_id`)
)
engine=innodb;

CREATE TABLE `LOG_EVENT` (
  `lev_id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
  `cmp_id` SMALLINT UNSIGNED NOT NULL,
  `let_id` TINYINT UNSIGNED NOT NULL,
  `ses_id` INTEGER UNSIGNED NOT NULL,
  `usr_id` INTEGER UNSIGNED NOT NULL,
  `lev_datetime` DATETIME NOT NULL,
  `lev_ip` INTEGER UNSIGNED,
  `lev_arg1` VARCHAR(64),
  `lev_arg2` VARCHAR(64),
  CONSTRAINT `PRIMARY_KEY` PRIMARY KEY (`lev_id`)
)
engine=innodb;

/*================================================================================*/
/* CREATE INDEXES                                                                 */
/*================================================================================*/

CREATE INDEX `IX_AUT_CONFIG1` ON `AUT_CONFIG` (`cmp_id`);

CREATE INDEX `IX_AUT_CONFIG2` ON `AUT_CONFIG` (`ccl_id`);

CREATE INDEX `CFG_ID` ON `AUT_CONFIG_VALUE` (`cfg_id`);

CREATE INDEX `IX_AUT_MODULE1` ON `AUT_MODULE` (`wrd_id`);

CREATE INDEX `IX_AUT_FUNCTIONALITY1` ON `AUT_FUNCTIONALITY` (`mdl_id`);

CREATE INDEX `IX_AUT_FUNCTIONALITY2` ON `AUT_FUNCTIONALITY` (`wrd_id`);

CREATE INDEX `IX_AUT_PAGE_TAB1` ON `AUT_PAGE_TAB` (`wrd_id`);

CREATE INDEX `IX_AUT_PAGE1` ON `AUT_PAGE` (`ptb_id`);

CREATE INDEX `IX_AUT_PAGE2` ON `AUT_PAGE` (`pag_id_org`);

CREATE INDEX `mnu_id` ON `AUT_PAGE` (`mnu_id`);

CREATE INDEX `wrd_id` ON `AUT_PAGE` (`wrd_id`);

CREATE INDEX `pag_id` ON `AUT_MENU` (`pag_id`);

CREATE INDEX `wrd_id` ON `AUT_MENU` (`wrd_id`);

CREATE INDEX `IX_AUT_MODULE_COMPANY1` ON `AUT_MODULE_COMPANY` (`mdl_id`);

CREATE INDEX `IX_AUT_MODULE_COMPANY2` ON `AUT_MODULE_COMPANY` (`cmp_id`);

CREATE INDEX `IX_AUT_PAGE_COMPANY1` ON `AUT_PAGE_COMPANY` (`pag_id`);

CREATE INDEX `IX_AUT_PAGE_COMPANY2` ON `AUT_PAGE_COMPANY` (`cmp_id`);

CREATE INDEX `IX_AUT_PRO_PAG1` ON `AUT_PRO_PAG` (`pag_id`, `pro_id`);

CREATE INDEX `IX_FK_AUT_ROLE_GROUP1` ON `AUT_ROLE_GROUP` (`wrd_id`);

CREATE INDEX `cmp_id` ON `AUT_ROLE` (`cmp_id`);

CREATE INDEX `IX_FK_AUT_ROLE` ON `AUT_ROLE` (`rlg_id`);

CREATE UNIQUE INDEX `IX_AUT_PRO_ROL1` ON `AUT_PRO_ROL` (`rol_id`, `pro_id`);

CREATE INDEX `IX_AUT_PRO_ROL3` ON `AUT_PRO_ROL` (`cmp_id`);

CREATE UNIQUE INDEX `IX_AUT_ROL_FLG1` ON `AUT_ROL_FLG` (`rol_id`, `rfl_id`);

CREATE INDEX `IX_AUT_ROL_FUN3` ON `AUT_ROL_FUN` (`cmp_id`);

CREATE INDEX `cmp_id` ON `AUT_USR_ROL` (`cmp_id`);

CREATE INDEX `WRD_ID` ON `LOG_EVENT_TYPE` (`wrd_id`);

CREATE INDEX `cmp_id` ON `LOG_EVENT` (`cmp_id`);

CREATE INDEX `IX_LOG_EVENT1` ON `LOG_EVENT` (`ses_id`);

CREATE INDEX `usr_id` ON `LOG_EVENT` (`usr_id`);

/*================================================================================*/
/* CREATE FOREIGN KEYS                                                            */
/*================================================================================*/

ALTER TABLE `AUT_CONFIG`
  ADD CONSTRAINT `FK_AUT_CONFIG_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`);

ALTER TABLE `AUT_CONFIG`
  ADD CONSTRAINT `FK_AUT_CONFIG_AUT_CONFIG_CLASS`
  FOREIGN KEY (`ccl_id`) REFERENCES `AUT_CONFIG_CLASS` (`ccl_id`);

ALTER TABLE `AUT_CONFIG_VALUE`
  ADD CONSTRAINT `AUT_CONFIG_VALUE_ibfk_2`
  FOREIGN KEY (`cfg_id`) REFERENCES `AUT_CONFIG` (`cfg_id`);

ALTER TABLE `AUT_MODULE`
  ADD CONSTRAINT `FK_AUT_MODULE_ABC_BABEL_WORD`
  FOREIGN KEY (`wrd_id`) REFERENCES `ABC_BABEL_WORD` (`wrd_id`);

ALTER TABLE `AUT_FUNCTIONALITY`
  ADD CONSTRAINT `FK_AUT_FUNCTIONALITY_ABC_BABEL_WORD`
  FOREIGN KEY (`wrd_id`) REFERENCES `ABC_BABEL_WORD` (`wrd_id`);

ALTER TABLE `AUT_FUNCTIONALITY`
  ADD CONSTRAINT `FK_AUT_FUNCTIONALITY_AUT_MODULE`
  FOREIGN KEY (`mdl_id`) REFERENCES `AUT_MODULE` (`mdl_id`);

ALTER TABLE `AUT_PAGE_TAB`
  ADD CONSTRAINT `FK_AUT_PAGE_TAB_ABC_BABEL_WORD`
  FOREIGN KEY (`wrd_id`) REFERENCES `ABC_BABEL_WORD` (`wrd_id`);

ALTER TABLE `AUT_PAGE`
  ADD CONSTRAINT `FK_AUT_PAGE_ABC_BABEL_WORD`
  FOREIGN KEY (`wrd_id`) REFERENCES `ABC_BABEL_WORD` (`wrd_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `AUT_PAGE`
  ADD CONSTRAINT `AUT_PAGE_ibfk_4`
  FOREIGN KEY (`mnu_id`) REFERENCES `AUT_MENU` (`mnu_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `AUT_PAGE`
  ADD CONSTRAINT `FK_AUT_PAGE_AUT_PAGE`
  FOREIGN KEY (`pag_id_org`) REFERENCES `AUT_PAGE` (`pag_id`);

ALTER TABLE `AUT_PAGE`
  ADD CONSTRAINT `FK_AUT_PAGE_AUT_PAGE_TAB`
  FOREIGN KEY (`ptb_id`) REFERENCES `AUT_PAGE_TAB` (`ptb_id`);

ALTER TABLE `AUT_MENU`
  ADD CONSTRAINT `FK_AUT_MENU_ABC_BABEL_WORD`
  FOREIGN KEY (`wrd_id`) REFERENCES `ABC_BABEL_WORD` (`wrd_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `AUT_MENU`
  ADD CONSTRAINT `AUT_MENU_ibfk_5`
  FOREIGN KEY (`pag_id`) REFERENCES `AUT_PAGE` (`pag_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `AUT_MODULE_COMPANY`
  ADD CONSTRAINT `FK_AUT_MODULE_COMPANY_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`);

ALTER TABLE `AUT_MODULE_COMPANY`
  ADD CONSTRAINT `FK_AUT_MODULE_COMPANY_AUT_MODULE`
  FOREIGN KEY (`mdl_id`) REFERENCES `AUT_MODULE` (`mdl_id`);

ALTER TABLE `AUT_PAG_FUN`
  ADD CONSTRAINT `FK_AUT_PAG_FUN_AUT_FUNCTIONALITY`
  FOREIGN KEY (`fun_id`) REFERENCES `AUT_FUNCTIONALITY` (`fun_id`);

ALTER TABLE `AUT_PAG_FUN`
  ADD CONSTRAINT `FK_AUT_PAG_FUN_AUT_PAGE`
  FOREIGN KEY (`pag_id`) REFERENCES `AUT_PAGE` (`pag_id`);

ALTER TABLE `AUT_PAGE_COMPANY`
  ADD CONSTRAINT `FK_AUT_PAGE_COMPANY_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`);

ALTER TABLE `AUT_PAGE_COMPANY`
  ADD CONSTRAINT `FK_AUT_PAGE_COMPANY_AUT_PAGE`
  FOREIGN KEY (`pag_id`) REFERENCES `AUT_PAGE` (`pag_id`);

ALTER TABLE `AUT_PRO_PAG`
  ADD CONSTRAINT `FK_AUT_PRO_PAG_ABC_AUTH_PROFILE`
  FOREIGN KEY (`pro_id`) REFERENCES `ABC_AUTH_PROFILE` (`pro_id`)
  ON DELETE CASCADE;

ALTER TABLE `AUT_PRO_PAG`
  ADD CONSTRAINT `FK_AUT_PRO_PAG_AUT_PAGE`
  FOREIGN KEY (`pag_id`) REFERENCES `AUT_PAGE` (`pag_id`)
  ON DELETE CASCADE;

ALTER TABLE `AUT_ROLE_GROUP`
  ADD CONSTRAINT `FK_AUT_ROLE_GROUP_ABC_BABEL_WORD`
  FOREIGN KEY (`wrd_id`) REFERENCES `ABC_BABEL_WORD` (`wrd_id`);

ALTER TABLE `AUT_ROLE`
  ADD CONSTRAINT `FK_AUT_ROLE_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `AUT_ROLE`
  ADD CONSTRAINT `FK_AUT_ROLE_AUT_ROLE_GROUP`
  FOREIGN KEY (`rlg_id`) REFERENCES `AUT_ROLE_GROUP` (`rlg_id`);

ALTER TABLE `AUT_PRO_ROL`
  ADD CONSTRAINT `FK_AUT_PRO_ROL_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`);

ALTER TABLE `AUT_PRO_ROL`
  ADD CONSTRAINT `FK_AUT_PRO_ROL_ABC_AUTH_PROFILE`
  FOREIGN KEY (`pro_id`) REFERENCES `ABC_AUTH_PROFILE` (`pro_id`)
  ON DELETE CASCADE;

ALTER TABLE `AUT_PRO_ROL`
  ADD CONSTRAINT `FK_AUT_PRO_ROL_AUT_ROLE`
  FOREIGN KEY (`rol_id`) REFERENCES `AUT_ROLE` (`rol_id`);

ALTER TABLE `AUT_ROL_FLG`
  ADD CONSTRAINT `FK_AUT_ROL_FLG_AUT_FLAG`
  FOREIGN KEY (`rfl_id`) REFERENCES `AUT_FLAG` (`rfl_id`);

ALTER TABLE `AUT_ROL_FLG`
  ADD CONSTRAINT `FK_AUT_ROL_FLG_AUT_ROLE`
  FOREIGN KEY (`rol_id`) REFERENCES `AUT_ROLE` (`rol_id`);

ALTER TABLE `AUT_ROL_FUN`
  ADD CONSTRAINT `FK_AUT_ROL_FUN_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`);

ALTER TABLE `AUT_ROL_FUN`
  ADD CONSTRAINT `FK_AUT_ROL_FUN_AUT_FUNCTIONALITY`
  FOREIGN KEY (`fun_id`) REFERENCES `AUT_FUNCTIONALITY` (`fun_id`);

ALTER TABLE `AUT_ROL_FUN`
  ADD CONSTRAINT `FK_AUT_ROL_FUN_AUT_ROLE`
  FOREIGN KEY (`rol_id`) REFERENCES `AUT_ROLE` (`rol_id`);

ALTER TABLE `AUT_USR_ROL`
  ADD CONSTRAINT `FK_AUT_USR_ROL_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `AUT_USR_ROL`
  ADD CONSTRAINT `FK_AUT_USR_ROL_ABC_AUTH_USER`
  FOREIGN KEY (`usr_id`) REFERENCES `ABC_AUTH_USER` (`usr_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `AUT_USR_ROL`
  ADD CONSTRAINT `AUT_USR_ROL_ibfk_3`
  FOREIGN KEY (`rol_id`) REFERENCES `AUT_ROLE` (`rol_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

ALTER TABLE `LOG_EVENT`
  ADD CONSTRAINT `FK_LOG_EVENT_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`);

ALTER TABLE `LOG_EVENT`
  ADD CONSTRAINT `FK_LOG_EVENT_ABC_AUTH_USER`
  FOREIGN KEY (`usr_id`) REFERENCES `ABC_AUTH_USER` (`usr_id`);

ALTER TABLE `LOG_EVENT`
  ADD CONSTRAINT `LOG_EVENT_ibfk_3`
  FOREIGN KEY (`let_id`) REFERENCES `LOG_EVENT_TYPE` (`let_id`)
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

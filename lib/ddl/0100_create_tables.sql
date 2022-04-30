/*================================================================================*/
/* DDL SCRIPT                                                                     */
/*================================================================================*/
/*  Title    : PhpPlaisio                                                         */
/*  FileName : core.ecm                                                           */
/*  Platform : MariaDB 10.x                                                       */
/*  Version  :                                                                    */
/*  Date     : Saturday, April 30, 2022                                           */
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

/*================================================================================*/
/* CREATE INDEXES                                                                 */
/*================================================================================*/

CREATE INDEX `IX_AUT_CONFIG1` ON `AUT_CONFIG` (`cmp_id`);

CREATE INDEX `IX_AUT_CONFIG2` ON `AUT_CONFIG` (`ccl_id`);

CREATE INDEX `CFG_ID` ON `AUT_CONFIG_VALUE` (`cfg_id`);

/*================================================================================*/
/* CREATE FOREIGN KEYS                                                            */
/*================================================================================*/

ALTER TABLE `AUT_CONFIG`
  ADD CONSTRAINT `FK_AUT_CONFIG_AUT_CONFIG_CLASS`
  FOREIGN KEY (`ccl_id`) REFERENCES `AUT_CONFIG_CLASS` (`ccl_id`);

ALTER TABLE `AUT_CONFIG`
  ADD CONSTRAINT `FK_AUT_CONFIG_ABC_AUTH_COMPANY`
  FOREIGN KEY (`cmp_id`) REFERENCES `ABC_AUTH_COMPANY` (`cmp_id`);

ALTER TABLE `AUT_CONFIG_VALUE`
  ADD CONSTRAINT `AUT_CONFIG_VALUE_ibfk_2`
  FOREIGN KEY (`cfg_id`) REFERENCES `AUT_CONFIG` (`cfg_id`);

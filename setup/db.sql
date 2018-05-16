-- ****************** SqlDBM: MySQL ******************;
-- ***************************************************;

DROP TABLE `dbo`.`billing_detail`;


DROP TABLE `dbo`.`product`;


DROP TABLE `dbo`.`pages`;


DROP TABLE `dbo`.`category`;


DROP TABLE `dbo`.`billing`;



-- ************************************** `dbo`.`pages`

CREATE TABLE `dbo`.`pages`
(
 `page_id`  BIGINT NOT NULL ,
 `contents` VARCHAR(MAX) NOT NULL ,
 `created`  DATETIME NOT NULL ,
 `modify`   DATETIME NOT NULL ,

PRIMARY KEY (`page_id`)
);





-- ************************************** `dbo`.`category`

CREATE TABLE `dbo`.`category`
(
 `category_id` BIGINT NOT NULL ,
 `name_en`     VARCHAR(100) NOT NULL ,
 `name_th`     VARCHAR(100) NOT NULL ,
 `description` VARCHAR(255) NOT NULL ,
 `created`     DATETIME NOT NULL ,
 `modify`      DATETIME NOT NULL ,

PRIMARY KEY (`category_id`)
);





-- ************************************** `dbo`.`billing`

CREATE TABLE `dbo`.`billing`
(
 `bill_id`      BIGINT NOT NULL ,
 `invoice_no`   VARCHAR(45) ,
 `invoice_date` DATETIME NOT NULL ,
 `delivery`     DATETIME NOT NULL ,
 `payment_term` VARCHAR(45) ,
 `vat`          INT NOT NULL ,
 `firstname`    VARCHAR(255) NOT NULL ,
 `lastname`     VARCHAR(255) NOT NULL ,
 `address1`     VARCHAR(MAX) NOT NULL ,
 `address2`     VARCHAR(MAX) ,
 `zipcode`      VARCHAR(20) ,
 `city`         VARCHAR(45) ,
 `country`      VARCHAR(45) ,
 `email`        VARCHAR(45) ,
 `notes`        VARCHAR(100) ,
 ` tel`         VARCHAR(16) NOT NULL ,
 `status`       VARCHAR(20) NOT NULL ,
 `created`      DATETIME NOT NULL ,
 `modify`       DATETIME NOT NULL ,

PRIMARY KEY (`bill_id`)
);





-- ************************************** `dbo`.`product`

CREATE TABLE `dbo`.`product`
(
 `product_id`  BIGINT NOT NULL ,
 `category_id` BIGINT NOT NULL ,
 `name_en`     VARCHAR(100) NOT NULL ,
 `name_th`     VARCHAR(100) NOT NULL ,
 `price`       DOUBLE NOT NULL ,
 `size`        VARCHAR(45) NOT NULL ,
 `description` VARCHAR(255) NOT NULL ,
 `created`     DATETIME NOT NULL ,
 `modify`      DATETIME NOT NULL ,

PRIMARY KEY (`product_id`),
-- SKIP: `fkIdx_93
);





-- ************************************** `dbo`.`billing_detail`

CREATE TABLE `dbo`.`billing_detail`
(
 `bill_detail_id` BIGINT NOT NULL ,
 `bill_id`        BIGINT NOT NULL ,
 `product_id`     BIGINT NOT NULL ,
 `description`    VARCHAR(255) NOT NULL ,
 `price`          DOUBLE NOT NULL ,
 `qty`            INT NOT NULL ,

PRIMARY KEY (`bill_detail_id`),
-- SKIP: `fkIdx_37`
-- SKIP: `fkIdx_66
);


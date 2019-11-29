------------
-- TABLES --
------------

-- drop tables if they exist
DROP TABLE IF EXISTS `image`;
DROP TABLE IF EXISTS `choice`;
DROP TABLE IF EXISTS `category`;
DROP TABLE IF EXISTS `user`;


-- create user table
CREATE TABLE `user` (
  `name` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- insert data into user table
LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES ('mjbelow','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user2','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user3','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user4','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user5','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user6','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user7','d74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),('user8','11507a0e2f5e69d5dfa40a62a1bd7b6ee57e6bcd85c67c9b8431b36fff21c437');
UNLOCK TABLES;

-- create category table
CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`username`),
  KEY `category_user` (`username`),
  CONSTRAINT `category_user` FOREIGN KEY (`username`) REFERENCES `user` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- insert data into category table
LOCK TABLES `category` WRITE;
INSERT INTO `category` VALUES (1,'Tennis','mjbelow'),(2,'Animals','mjbelow');
UNLOCK TABLES;

-- create choice table
CREATE TABLE `choice` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`category_id`,`username`),
  KEY `choice_category` (`category_id`,`username`),
  CONSTRAINT `choice_category` FOREIGN KEY (`category_id`, `username`) REFERENCES `category` (`id`, `username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- insert data into choice table
LOCK TABLES `choice` WRITE;
INSERT INTO `choice` VALUES (1,'Rafael Nadal',1,'mjbelow'),(1,'Sloth',2,'mjbelow'),(2,'Roger Federer',1,'mjbelow'),(2,'Koala Bear',2,'mjbelow'),(3,'Maria Sharapova',1,'mjbelow'),(3,'Hippo',2,'mjbelow'),(4,'Serena Williams',1,'mjbelow'),(4,'Monkey',2,'mjbelow'),(5,'Men',1,'mjbelow'),(5,'Lion',2,'mjbelow'),(6,'Women',1,'mjbelow'),(6,'Goat',2,'mjbelow'),(7,'Young',1,'mjbelow'),(7,'Tiger',2,'mjbelow'),(8,'Sheep',2,'mjbelow'),(9,'Lemur',2,'mjbelow'),(10,'Fox',2,'mjbelow');
UNLOCK TABLES;


-- create image table
CREATE TABLE `image` (
  `name` varchar(45) NOT NULL,
  `category_id` int(11) NOT NULL,
  `choice_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`name`,`category_id`,`choice_id`,`username`),
  KEY `image_choice` (`choice_id`,`category_id`,`username`),
  CONSTRAINT `image_choice` FOREIGN KEY (`choice_id`, `category_id`, `username`) REFERENCES `choice` (`id`, `category_id`, `username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- insert data into image table
LOCK TABLES `image` WRITE;
INSERT INTO `image` VALUES ('3pZCz3a0Ma.jpg',1,1,'mjbelow'),('7rfsPC4FZg.jpg',1,1,'mjbelow'),('87SJpQhgFc.jpg',1,1,'mjbelow'),('akxXdXQlIw.jpg',1,1,'mjbelow'),('csX2Zv5pAD.jpg',1,1,'mjbelow'),('DnC1nes3s2.jpg',1,1,'mjbelow'),('hBp0CuLabH.jpg',1,1,'mjbelow'),('KGC8UlbNHR.jpg',1,1,'mjbelow'),('mTxXSFEBEJ.jpg',1,1,'mjbelow'),('NEdLUsZtCI.jpg',1,1,'mjbelow'),('nEwboYvVYY.jpg',1,1,'mjbelow'),('nnJcR8BdJ5.jpg',1,1,'mjbelow'),('okigOPUgVE.jpg',1,1,'mjbelow'),('sBUT1i5N21.jpg',1,1,'mjbelow'),('SmHQBmBost.webp',1,1,'mjbelow'),('UvUwnBth5i.webp',1,1,'mjbelow'),('wEHBJdS5r7.jpg',1,1,'mjbelow'),('xDRlBryoHg.jpg',1,1,'mjbelow'),('XTmfMCPJ6N.jpg',1,1,'mjbelow'),('ZyAihWkcBD.jpg',1,1,'mjbelow'),('twtA4EHYfY.jpg',2,1,'mjbelow'),('1wKm2xDSLm.jpg',1,2,'mjbelow'),('3pZCz3a0Ma.jpg',1,2,'mjbelow'),('6deGaiyPYX.jpg',1,2,'mjbelow'),('6qt7KJMtro.jpg',1,2,'mjbelow'),('7rfsPC4FZg.jpg',1,2,'mjbelow'),('AD7eeYTL13.jpg',1,2,'mjbelow'),('irel7oBk4H.jpg',1,2,'mjbelow'),('iy8L0faWIl.jpg',1,2,'mjbelow'),('KGC8UlbNHR.jpg',1,2,'mjbelow'),('mTxXSFEBEJ.jpg',1,2,'mjbelow'),('NEdLUsZtCI.jpg',1,2,'mjbelow'),('nnJcR8BdJ5.jpg',1,2,'mjbelow'),('xSmwVG1ZuC.jfif',1,2,'mjbelow'),('XTmfMCPJ6N.jpg',1,2,'mjbelow'),('Z3tvBTghj0.jfif',1,2,'mjbelow'),('ZyAihWkcBD.jpg',1,2,'mjbelow'),('W2H3rFqwoL.webp',2,2,'mjbelow'),('3pZCz3a0Ma.jpg',1,3,'mjbelow'),('EMPs6EYguW.jpg',1,3,'mjbelow'),('JvGKiMTnAk.jpg',1,3,'mjbelow'),('zkaB7ov5w3.jpg',1,3,'mjbelow'),('g4YTXdupEe.jpg',2,3,'mjbelow'),('3pZCz3a0Ma.jpg',1,4,'mjbelow'),('4yTUc9wwdV.jpg',1,4,'mjbelow'),('ekCHlOXvXk.webp',1,4,'mjbelow'),('SvzL1RIsb3.webp',1,4,'mjbelow'),('xSmwVG1ZuC.jfif',1,4,'mjbelow'),('IS5IM5ICxS.jpg',2,4,'mjbelow'),('1wKm2xDSLm.jpg',1,5,'mjbelow'),('3pZCz3a0Ma.jpg',1,5,'mjbelow'),('6deGaiyPYX.jpg',1,5,'mjbelow'),('6qt7KJMtro.jpg',1,5,'mjbelow'),('7rfsPC4FZg.jpg',1,5,'mjbelow'),('87SJpQhgFc.jpg',1,5,'mjbelow'),('AD7eeYTL13.jpg',1,5,'mjbelow'),('akxXdXQlIw.jpg',1,5,'mjbelow'),('csX2Zv5pAD.jpg',1,5,'mjbelow'),('DnC1nes3s2.jpg',1,5,'mjbelow'),('hBp0CuLabH.jpg',1,5,'mjbelow'),('irel7oBk4H.jpg',1,5,'mjbelow'),('iy8L0faWIl.jpg',1,5,'mjbelow'),('KGC8UlbNHR.jpg',1,5,'mjbelow'),('mTxXSFEBEJ.jpg',1,5,'mjbelow'),('NEdLUsZtCI.jpg',1,5,'mjbelow'),('nEwboYvVYY.jpg',1,5,'mjbelow'),('nnJcR8BdJ5.jpg',1,5,'mjbelow'),('okigOPUgVE.jpg',1,5,'mjbelow'),('sBUT1i5N21.jpg',1,5,'mjbelow'),('SmHQBmBost.webp',1,5,'mjbelow'),('UvUwnBth5i.webp',1,5,'mjbelow'),('wEHBJdS5r7.jpg',1,5,'mjbelow'),('xDRlBryoHg.jpg',1,5,'mjbelow'),('xSmwVG1ZuC.jfif',1,5,'mjbelow'),('XTmfMCPJ6N.jpg',1,5,'mjbelow'),('Z3tvBTghj0.jfif',1,5,'mjbelow'),('ZyAihWkcBD.jpg',1,5,'mjbelow'),('A2Y3Qx2lso.jpg',2,5,'mjbelow'),('PFvgDimwGv.jpg',2,5,'mjbelow'),('3pZCz3a0Ma.jpg',1,6,'mjbelow'),('4yTUc9wwdV.jpg',1,6,'mjbelow'),('ekCHlOXvXk.webp',1,6,'mjbelow'),('EMPs6EYguW.jpg',1,6,'mjbelow'),('JvGKiMTnAk.jpg',1,6,'mjbelow'),('SvzL1RIsb3.webp',1,6,'mjbelow'),('xSmwVG1ZuC.jfif',1,6,'mjbelow'),('zkaB7ov5w3.jpg',1,6,'mjbelow'),('AJtGD2HZjs.jpg',2,6,'mjbelow'),('epREGHHWwk.jpg',2,6,'mjbelow'),('tAdkphFiVG.jpg',2,6,'mjbelow'),('1wKm2xDSLm.jpg',1,7,'mjbelow'),('DnC1nes3s2.jpg',1,7,'mjbelow'),('hBp0CuLabH.jpg',1,7,'mjbelow'),('iy8L0faWIl.jpg',1,7,'mjbelow'),('NEdLUsZtCI.jpg',1,7,'mjbelow'),('nEwboYvVYY.jpg',1,7,'mjbelow'),('xDRlBryoHg.jpg',1,7,'mjbelow'),('XTmfMCPJ6N.jpg',1,7,'mjbelow'),('Z3tvBTghj0.jfif',1,7,'mjbelow'),('dRFGkx7hVb.jpg',2,7,'mjbelow'),('PFvgDimwGv.jpg',2,7,'mjbelow'),('epREGHHWwk.jpg',2,8,'mjbelow'),('fIT6KfnSOT.jpg',2,8,'mjbelow'),('OpwUCSpnmJ.jpg',2,8,'mjbelow'),('tAdkphFiVG.jpg',2,8,'mjbelow'),('I9K7CGGNGd.jpg',2,9,'mjbelow'),('xknQMH4Xhl.jpg',2,10,'mjbelow');
UNLOCK TABLES;


-------------
--  VIEWS  --
-------------

-- drop views if they exist
DROP VIEW IF EXISTS `my_index`;
DROP VIEW IF EXISTS `my_images`;
DROP VIEW IF EXISTS `my_options`;


-- my_options used to generate menu and upload options
CREATE VIEW `my_options` AS
    SELECT 
        `category`.`username` AS `username`,
        `category`.`id` - 1 AS `id`,
        `category`.`name` AS `category`,
        `choice`.`name` AS `choice`
    FROM
        (`category`
        LEFT JOIN `choice` ON (`category`.`id` = `choice`.`category_id`
            AND `category`.`username` = `choice`.`username`))
    ORDER BY `category`.`username` , `category`.`id` , `choice`.`id`;


-- my_images used to build my_index
CREATE VIEW `my_images` AS
    SELECT 
        `image`.`username` AS `username`,
        `image`.`name` AS `name`,
        `image`.`category_id` AS `category_id`,
        SUM(POW(2, `image`.`choice_id` - 1)) AS `choices`
    FROM
        `image`
    GROUP BY `image`.`username` , `image`.`category_id` , `image`.`name`
    ORDER BY `image`.`username` , `image`.`category_id` , `choices` , `image`.`name`;


-- my_index used for menu logic, which is used to dynamically generate sql query for image page
CREATE VIEW `my_index` AS
    SELECT 
        `my_images`.`username` AS `username`,
        `my_images`.`category_id` AS `category`,
        `my_images`.`choices` AS `choice`,
        COUNT(0) AS `count`
    FROM
        `my_images`
    GROUP BY `my_images`.`username` , `my_images`.`category_id` , `my_images`.`choices`
    ORDER BY `my_images`.`username` , `my_images`.`category_id` , `my_images`.`choices`;
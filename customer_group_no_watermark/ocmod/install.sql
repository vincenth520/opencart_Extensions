ALTER TABLE  `oc_customer_group_description`  ADD  `no_watermark_num` int NOT NULL default 0;
ALTER TABLE  `oc_customer`  ADD  `no_watermark_num` int NOT NULL default 0;
ALTER TABLE  `oc_customer`  ADD  `no_watermark_clear_month` int NOT NULL default 0;
ALTER TABLE  `oc_customer`  ADD  `nowatermark` tinyint NOT NULL default 0;
CREATE TABLE `oc_download_no_watermark`(id int primary key auto_increment,product_id int,customer_id int);
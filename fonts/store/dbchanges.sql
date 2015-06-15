ALTER TABLE  `bs_purchases` ADD  `transaction_id` VARCHAR( 250 ) NOT NULL AFTER  `date_time` ;

ALTER TABLE  `bs_purchases` ADD  `total_price` DECIMAL( 10, 2 ) NOT NULL AFTER  `transaction_id` ;

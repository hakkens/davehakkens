CREATE TABLE IF NOT EXISTS pp_pins (
  ID              BIGINT(20) unsigned   NOT NULL AUTO_INCREMENT,
  user_ID         BIGINT(20) unsigned   NOT NULL,
  name            VARCHAR(255)          NOT NULL,
  lat             DOUBLE,
  lng             DOUBLE,
  description     TEXT,
  address         TEXT,
  website         VARCHAR(255),
  filters         JSON,
  imgs            JSON,
  status          VARCHAR(32)           NOT NULL,
  approval_status VARCHAR(32)           DEFAULT 0,
  created_date    DATETIME              DEFAULT CURRENT_TIMESTAMP,
  modified_date   DATETIME              ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(`ID`),
  KEY `user_ID` (`user_ID`)
);

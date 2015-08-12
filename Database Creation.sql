show databases;

-- drop database videorental;

create database videorental;

use videorental;

show tables;

CREATE TABLE CUSTOMER(
  custid Integer NOT NULL,
  firstname Varchar(20),
  lastname Varchar(20),
  status varchar(10),
  gender varchar(1),
  dob date,
  emailid varchar(30),
  address varchar(50),
  phoneno varchar(10),
  password VARCHAR(32),
  CONSTRAINT pk_CUSTOMER PRIMARY KEY (custid)
);

CREATE TABLE GENRE(
  genre_id Integer NOT NULL,
  name Varchar(20),
  CONSTRAINT pk_GENRE PRIMARY KEY (genre_id)
);

CREATE TABLE FILM(
  film_id Integer NOT NULL,
  title Varchar(50) NOT NULL,
  director Varchar(30) NOT NULL,
  production_company Varchar(50),
  genre_id Integer NOT NULL,
  minimal_age Integer,
  film_episode_id Integer,
  CONSTRAINT pk_FILM PRIMARY KEY (film_id),
  CONSTRAINT uk_title_director UNIQUE (title,director),
  CONSTRAINT belongsTo FOREIGN KEY (genre_id) REFERENCES GENRE (genre_id)
);

CREATE TABLE CUSTOMER_RATING(
  custid Integer NOT NULL,
  film_id Integer NOT NULL,
  rating Integer DEFAULT 3, -- Rating should be greater than 0. For this CHECK contraint has to be written. Unfortunately it is not supported in MySQL. So, this has to be handled in triggers.
  CONSTRAINT pk_CUSTOMER_RATING PRIMARY KEY (custid, film_id),
  CONSTRAINT gives FOREIGN KEY (custid) REFERENCES CUSTOMER (custid),
  CONSTRAINT isRated FOREIGN KEY (film_id) REFERENCES FILM (film_id)
);

CREATE TABLE MEDIUM(
  medium_id Integer NOT NULL,
  medium_type Varchar(20),
  CONSTRAINT pk_MEDIUM PRIMARY KEY (medium_id)
);

CREATE TABLE EXEMPLAR(
  exemplar_id Integer NOT NULL,
  exemplar_name varchar(30),
  address varchar(50),
  phone_no varchar(10),
  CONSTRAINT pk_EXEMPLAR PRIMARY KEY (exemplar_id)
);

create table MOVIESOFFERED(
  offered_id Integer NOT NULL,
  exemplar_id Integer NOT NULL,
  film_id Integer NOT NULL,
  medium_id Integer NOT NULL,
  price_per_day DECIMAL(4,2),
  availability_status varchar(1),
  CONSTRAINT pk_moviesoffered PRIMARY KEY (offered_id),
  CONSTRAINT isOfferedBy FOREIGN KEY (exemplar_id) REFERENCES EXEMPLAR (exemplar_id),
  CONSTRAINT isOfferedOn FOREIGN KEY (film_id) REFERENCES FILM (film_id),
  CONSTRAINT isAvailableOn FOREIGN KEY (medium_id) REFERENCES MEDIUM (medium_id)
);

CREATE TABLE BORROWING(
  borrowing_id Integer NOT NULL,
  offered_id Integer NOT NULL,
  custid Integer NOT NULL,
  start_date TIMESTAMP DEFAULT NOW(), -- end_date > start_date. For this CHECK contraint has to be written. Unfortunately it is not supported in MySQL. So, this has to be handled in triggers.
  end_date Date,
  total_price DECIMAL(4,2),
  VAT DECIMAL(4,2) DEFAULT 16,
  CONSTRAINT pk_BORROWING PRIMARY KEY (borrowing_id),
  CONSTRAINT borrowedFrom FOREIGN KEY (offered_id) REFERENCES MOVIESOFFERED (offered_id),
  CONSTRAINT isBorrowedBy FOREIGN KEY (custid) REFERENCES CUSTOMER (custid)
);
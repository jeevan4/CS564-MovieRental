use movierental;

####################################
-- INDEXES FOR CUSTOMER_RATING TABLE 
####################################
CREATE INDEX custrating_index ON customer_rating(custid);

SHOW INDEXES FROM customer_rating;

####################################
-- INDEXES FOR MOVIES OFFERED table
####################################
CREATE INDEX moffer_movie_index ON moviesoffered(movie_id);
CREATE INDEX moffer_exemplar_index ON moviesoffered(exemplar_id);


SHOW INDEXES FROM moviesoffered;

####################################
-- INDEXES FOR BORROWING TABLE 
####################################
CREATE INDEX borrow_custid_index ON borrowing(custid);
CREATE INDEX borrow_offerid_index ON borrowing(offered_id);

SHOW INDEXES FROM borrowing;

################################################
# Checking performance after adding Indexes
################################################

SELECT firstname, 
       lastname, 
       emailid, 
       borrowing_id, 
       start_date, 
       end_date, 
       e.exemplar_name, 
       mov.title, 
       med.medium_type, 
       total_price, 
       vat 
FROM   (SELECT c.firstname    AS firstname, 
               c.lastname     AS lastname, 
               c.emailid      AS emailid, 
               b.borrowing_id AS borrowing_id, 
               b.start_date   AS start_date, 
               b.end_date     AS end_date, 
               b.total_price  AS total_price, 
               b.vat          AS vat, 
               m.exemplar_id  AS exemplar_id, 
               m.movie_id     AS movie_id, 
               m.medium_id    AS medium_id 
        FROM   borrowing b 
               INNER JOIN customer c 
                       ON c.custid = b.custid -- and b.custid=@cus_id 
               INNER JOIN moviesoffered m 
                       ON b.offered_id = m.offered_id) ord 
       INNER JOIN exemplar e 
               ON e.exemplar_id = ord.exemplar_id 
       INNER JOIN movie mov 
               ON mov.movie_id = ord.movie_id 
       INNER JOIN medium med 
               ON med.medium_id = ord.medium_id; 
               
			
SELECT Concat(customer.lastname, ', ', customer.firstname) AS CUSTOMER_NAME, 
       title                                               AS FILMNAME, 
       rating                                              AS MY_RATING 
FROM   customer_rating cr 
       INNER JOIN movie m 
               ON m.movie_id = cr.movie_id 
       INNER JOIN customer 
               ON customer.custid = cr.custid 
WHERE  customer.custid = 'C10001'; 

SELECT title AS FILM_NAME 
FROM   moviesoffered 
       INNER JOIN movie 
               ON movie.movie_id = moviesoffered.movie_id 
       INNER JOIN exemplar 
               ON exemplar.exemplar_id = moviesoffered.exemplar_id 
WHERE  availability_status = 'Y' 
       AND exemplar_name = 'NETFLIX'; 

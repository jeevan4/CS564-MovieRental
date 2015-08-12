use movierental;

Select 'C10012' into @cus_id;

-- ############################################################################
-- <query-01>To concatenate first and last name to display a welcome message 
-- ############################################################################

Select concat(firstname,' ',lastname) as CustName from customer where custid='C10012';

############################################################
-- <query-02> To View order details of a customer :
############################################################

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

######################################################################################
-- <query-03>To Identiy Order details for a particular customer ordered by Start date:
######################################################################################

Insert into BORROWING values('BO00000564','O00000862','C10012','2014-06-18','2014-06-20',8,0.7);

SELECT Concat(firstname, "", lastname) AS CustName, 
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
                       ON c.custid = b.custid 
                          AND b.custid = 'C10012' 
               INNER JOIN moviesoffered m 
                       ON b.offered_id = m.offered_id) ord 
       INNER JOIN exemplar e 
               ON e.exemplar_id = ord.exemplar_id 
       INNER JOIN movie mov 
               ON mov.movie_id = ord.movie_id 
       INNER JOIN medium med 
               ON med.medium_id = ord.medium_id 
ORDER  BY start_date DESC; 

############################################################################
-- <query-04> SHOW ALL FILMS RATED BY A CUSTOMER :
###########################################################################

INSERT INTO CUSTOMER_RATING VALUES('C10001','M00000224',4);
INSERT INTO CUSTOMER_RATING VALUES('C10001','M00000360',4);
INSERT INTO CUSTOMER_RATING VALUES('C10001','M00000143',4);

SELECT Concat(customer.lastname, ', ', customer.firstname) AS CUSTOMER_NAME, 
       title                                               AS FILMNAME, 
       rating                                              AS MY_RATING 
FROM   customer_rating cr 
       INNER JOIN movie m 
               ON m.movie_id = cr.movie_id 
       INNER JOIN customer 
               ON customer.custid = cr.custid 
WHERE  customer.custid = 'C10001'; 
############################################################################
-- <query-05> SHOW ALL AVAILABLE FILMS WITH PARTICULAR EXEMPLAR
###########################################################################

SELECT title AS FILM_NAME 
FROM   moviesoffered 
       INNER JOIN movie 
               ON movie.movie_id = moviesoffered.movie_id 
       INNER JOIN exemplar 
               ON exemplar.exemplar_id = moviesoffered.exemplar_id 
WHERE  availability_status = 'Y' 
       AND exemplar_name = 'NETFLIX'; 
############################################################################
-- <query-06> AVERAGE RATING FOR A FILM
###########################################################################

INSERT INTO CUSTOMER_RATING VALUES('C10002','M00000224',1);
INSERT INTO CUSTOMER_RATING VALUES('C10012','M00000224',4);
INSERT INTO CUSTOMER_RATING VALUES('C10112','M00000224',3);

SELECT title                                  AS FILM_NAME, 
       Cast(Avg(cr.rating) AS DECIMAL(12, 1)) AS FILM_RATING 
FROM   customer_rating cr 
       INNER JOIN movie 
               ON movie.movie_id = cr.movie_id 
WHERE  movie.title = "infernal affairs (2002)"; 

############################################################################
-- <query-07> SHOW ALL THE CUSTOMERS AND THEIR RATINGS FOR A MOVIE
###########################################################################

SELECT DISTINCT Concat(customer.lastname, ', ', customer.firstname) AS NAME, 
                title                                               AS FILMNAME, 
                rating                                              AS MY_RATING 
FROM   customer_rating cr 
       INNER JOIN movie m 
               ON m.movie_id = cr.movie_id 
       INNER JOIN customer 
               ON customer.custid = cr.custid 
WHERE  m.title = "infernal affairs (2002)"; 

############################################################################
-- <query-08> Top 10 rated movies
###########################################################################

SELECT movie_id, 
       avg_rating 
FROM   (SELECT movie_id, 
               Avg(rating) AS AVG_RATING 
        FROM   customer_rating 
        GROUP  BY movie_id) A 
ORDER  BY A.avg_rating DESC 
LIMIT  10; 

############################################################################
-- <query-09> Top 3 per genre (union query)
###########################################################################

select A.movie_id, G.genre_id, A.AVG_RATING from
((select * from (SELECT MOVIE_ID, AVG(RATING) AS AVG_RATING FROM CUSTOMER_RATING WHERE MOVIE_ID IN 
               (SELECT MOVIE_ID FROM MOVIE WHERE GENRE_ID IN (SELECT GENRE_ID FROM GENRE WHERE NAME IN ('ADVENTURE')))
GROUP BY MOVIE_ID, RATING) A ORDER BY A.AVG_RATING DESC LIMIT 3)
UNION all
(select * from (SELECT MOVIE_ID, AVG(RATING) AS AVG_RATING FROM CUSTOMER_RATING WHERE MOVIE_ID IN 
               (SELECT MOVIE_ID FROM MOVIE WHERE GENRE_ID IN (SELECT GENRE_ID FROM GENRE WHERE NAME IN ('HORROR')))
GROUP BY MOVIE_ID, RATING) B ORDER BY B.AVG_RATING DESC LIMIT 3)) A, MOVIE M, Genre G where A.MOVIE_ID = M.MOVIE_ID and G.genre_id = M.genre_id;

###############################################################################
-- <query-10> Selecting top rated movies that are offered only through ONLINE medium
###############################################################################

SELECT A.movie_id, 
       m.title, 
       A.avg_rating 
FROM   (SELECT movie_id, 
               Avg(rating) AS AVG_RATING 
        FROM   customer_rating 
        GROUP  BY movie_id) A 
       INNER JOIN moviesoffered mov 
               ON mov.movie_id = A.movie_id 
       INNER JOIN medium med 
               ON mov.medium_id = med.medium_id 
                  AND medium_type = 'ONLINE' 
       INNER JOIN movie m 
               ON a.movie_id = m.movie_id 
WHERE  A.avg_rating = 5 
ORDER  BY A.avg_rating DESC 
LIMIT  10; 
use movierental;

################################################################
-- <insert-01> To Create an New Order when user selects a movie:
################################################################

-- Identifying movie offered medium and cost per day 

Select offered_id,price_per_day into @offrd_id,@cost from moviesoffered where movie_id='M00000025' and medium_id='ME001'
and exemplar_id='E0001';

-- Generating a new order id or borrowing_id for this order

Select LPAD(substring(max(borrowing_id),3,8) +1,8,0) into @borr_id from borrowing; 
Select concat('BO',@borr_id);

-- Indentifying customer_id , generally we can get from Session variable

Select 'c10014' into @cus_id;

-- Date operations for start date,end_date and date difference

set @start_date = current_timestamp();
set @end_date = '2015-04-02';
select datediff(@end_date,@start_date) into @date_diff;

Insert into borrowing values (concat('BO',@borr_id),@offrd_id,@cus_id,@start_date,@end_date,@cost*@date_diff,cast((@cost*@date_diff)*0.07 as decimal(4,2)));

Select * from borrowing where custid='c10014';

########################################################################################
-- <insert-02> To insert customer rating if and only if the customer purchased that movie
########################################################################################
-- Customer id 'C10012' wants to give rating to 'M00000862' movie
-- This can be insterted if and only if he already borrowed

INSERT INTO customer_rating 
SELECT custid, 
       movie_id, 
       3 
FROM   (SELECT custid, 
               movie_id 
        FROM   customer 
               JOIN movie 
                 ON custid = 'C10012' 
                    AND movie_id = 'M00000862') a 
WHERE  EXISTS (SELECT custid, 
                      movie_id 
               FROM   (SELECT custid, 
                              movie_id 
                       FROM   borrowing b 
                              INNER JOIN moviesoffered m 
                                      ON m.offered_id = b.offered_id) c 
               WHERE  a.custid = c.custid 
                      AND a.movie_id = c.movie_id);

select * from customer_rating where custid='C10012';

########################################################################
-- <delete-01>To remove movies offered from a exemplar if he is removed 
########################################################################
-- Suppose we are no more providing AMAZON as exemplar then we wanted to delete all movies offered by 
-- AMAZON like this :

SET FOREIGN_KEY_CHECKS=0;

DELETE FROM moviesoffered 
WHERE  NOT EXISTS (SELECT * 
                   FROM   exemplar e 
                   WHERE  e.exemplar_id = moviesoffered.exemplar_id 
                          AND e.exemplar_id <> 'E0002'); 
                          
SET FOREIGN_KEY_CHECKS=1;


############################################################
-- <update-01>To update the availability status moviesoffered table form a exemplar if he is removed 
############################################################
-- Suppose we are no more providing AMAZON as exemplar then we wanted to delete all movies offered by 
-- AMAZON like this :

UPDATE moviesoffered 
SET availability_status = 'N' 
WHERE  NOT EXISTS (SELECT * 
                   FROM   exemplar e 
                   WHERE  e.exemplar_id = moviesoffered.exemplar_id 
                          AND e.exemplar_id <> 'E0005'); 

############################################################
-- <delete-02> DELETE RATING GIVEN BY A USER FOR A FILM
############################################################

DELETE FROM customer_rating 
WHERE  custid = 'C10001' 
       AND movie_id = (SELECT movie_id 
                       FROM   movie 
                       WHERE  title = "infernal affairs (2002)"); 
                       
############################################################
-- <update-02> UPDATE RATING OF A FILM BY A USER
############################################################

UPDATE customer_rating, 
       customer 
SET    rating = '3' 
WHERE  customer.custid = 'C10001' 
       AND movie_id = (SELECT movie_id 
                       FROM   movie 
                       WHERE  title = "infernal affairs (2002)"); 
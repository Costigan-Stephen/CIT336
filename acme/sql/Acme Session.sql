--SHOW CLIENTS
select *
from clients;

--ADD TONY STARK
INSERT INTO clients(clientid, clientFirstName, clientLastName, 
clientEmail, clientPassword, clientLevel, comments) 
VALUES ('','Tony','Stark', 'tony@starkent.com',
'Iam1ronM@n','1','I am the real Ironman');

select *
from clients;

--CHANGE TONY'S ACCESS
Update clients
SET
    clientLevel = '3'
WHERE clientid = '1';

select *
from clients;


--SHOW INVENTORY
select *
from inventory;


--CHANGE NYLON ROPE to CLIMBING ROPE
Update inventory
SET
    invName = 'Climbing Rope',
    invStyle = 'Climbing',
    invDescription = Replace (invDescription, 'nylon rope', 'climbing rope')
WHERE invid = '15';

select *
from inventory;


--INNER JOIN INVNAME TO MISC
SELECT invName 
FROM inventory 
JOIN categories
  ON categories.categoryId = inventory.categoryId
  WHERE inventory.categoryId = '3';

  select *
from inventory;


--DELETE RECORD
DELETE FROM inventory WHERE invName = 'Koenigsegg CCX Car';
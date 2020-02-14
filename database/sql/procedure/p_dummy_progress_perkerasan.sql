DELIMITER #
CREATE PROCEDURE dummy_progress_perkerasan()
BEGIN

DECLARE finished INTEGER DEFAULT 0;
DECLARE cursor_ID INT;
DECLARE v_max INT UNSIGNED DEFAULT 12;
DECLARE v_counter INT UNSIGNED DEFAULT 1;
DECLARE cursor_item CURSOR FOR SELECT DISTINCT id FROM TM_ROAD;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;

OPEN cursor_item;

item_loop: LOOP
	FETCH cursor_item INTO cursor_ID;
	IF finished = 1 THEN 
            LEAVE item_loop;
        END IF;
        SET v_counter = 1;
	WHILE v_counter <= v_max DO
		INSERT INTO TR_ROAD_PAVEMENT_PROGRESS (road_id, LENGTH, MONTH, YEAR, updated_by, created_at, updated_at)
		VALUES(cursor_ID,10,v_counter,2020,'dummy',NOW(), NOW());
		SET v_counter=v_counter+1;
	END WHILE;
END LOOP item_loop;

CLOSE cursor_item;
END #
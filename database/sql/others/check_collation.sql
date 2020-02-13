SELECT default_character_set_name FROM information_schema.SCHEMATA 
WHERE schema_name = "taproad";

SELECT CCSA.character_set_name, CCSA.COLLATION_NAME FROM information_schema.`TABLES` T,
       information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA
WHERE CCSA.collation_name = T.table_collation
  AND T.table_schema = "taproad"
  AND T.table_name in( "TM_ROAD","TM_GENERAL_DATA");

SELECT character_set_name, COLLATION_NAME, COLUMN_NAME FROM information_schema.`COLUMNS` 
WHERE table_schema = "taproad"
  AND table_name in("TM_ROAD","TM_GENERAL_DATA");
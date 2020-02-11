create or replace view V_LIST_HISTORY_STATUS as
SELECT trs.road_id ,trs.road_code,trs.road_name,tmr.total_length, tmrs.status_name, tmrc.category_name,trs.segment, 
trs.updated_by, trs.created_at, trs.updated_at
FROM TR_ROAD_STATUS trs 
JOIN TM_ROAD tmr ON trs.road_id = tmr.id 
JOIN TM_ROAD_STATUS tmrs ON tmrs.id = trs.status_id
JOIN TM_ROAD_CATEGORY tmrc ON tmrc.id = trs.category_id
WHERE  tmr.deleted_at IS NULL
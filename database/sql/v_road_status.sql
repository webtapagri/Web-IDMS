create or replace view V_LIST_HISTORY_STATUS as
select trs.status_id, trs.id,trs.road_id ,trs.road_code,trs.road_name,vrl.total_length, tmrs.status_name
, tmrc.category_name, trs.updated_by
, trs.created_at, trs.updated_at, trs.segment
from TR_ROAD_STATUS trs
join TM_ROAD_STATUS tmrs on tmrs.id = trs.status_id
join TM_ROAD_CATEGORY tmrc on tmrc.id = trs.category_id
, V_ROAD_LOG vrl,
TM_ROAD tmr 
where vrl.id = trs.road_id and tmr.id = vrl.id and tmr.deleted_at is null 
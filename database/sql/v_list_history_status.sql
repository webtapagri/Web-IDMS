create or replace view V_LIST_HISTORY_STATUS as
select trs.id,trs.road_id ,vrl.road_code,vrl.road_name,vrl.total_length, tmrs.status_name, tmrc.category_name, trs.updated_by, trs.created_at, trs.updated_at
from TR_ROAD_STATUS trs 
join V_ROAD_LOG vrl on trs.road_id = vrl.id
join TM_ROAD tmr on vrl.id = tmr.id 
join TM_ROAD_STATUS tmrs on tmrs.id = trs.status_id
join TM_ROAD_CATEGORY tmrc on tmrc.id = trs.category_id
where vrl.id = trs.road_id and tmr.id = vrl.id and trs.road_id = '7' and tmr.deleted_at is null 
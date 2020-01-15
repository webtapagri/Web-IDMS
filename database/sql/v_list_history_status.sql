create or replace view V_LIST_HISTORY_STATUS as
select trs.id,trs.road_id ,vrl.road_code,vrl.road_name,vrl.total_length, vrl.status_name, vrl.category_name, trs.updated_by, trs.created_at, trs.updated_at
from TR_ROAD_STATUS trs, V_ROAD_LOG vrl,
TM_ROAD tmr 
join TM_ROAD_STATUS tmrs on tmrs.id = (select status_id from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1)
join TM_ROAD_CATEGORY tmrc on tmrc.id = (select category_id from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1)
where vrl.id = trs.road_id and tmr.id = vrl.id and tmr.deleted_at is null 
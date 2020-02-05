create or replace view V_ROAD as 
select tmr.company_name, tmr.estate_name, tmr.afdeling_name, tmr.block_name, tmrs.status_name, tmrc.category_name, 
tmr.total_length,
tmr.asset_code,
tmr.segment,
tmr.id, tmr.company_code, tmr.werks, tmr.afdeling_code, tmr.block_code, tmr.road_code, tmr.road_name, tmr.status_pekerasan, tmr.status_aktif, tmr.deleted_at, tmr.created_at, tmr.updated_at, tmr.estate_code, tmr.updated_by 
from TM_ROAD tmr
join TM_ROAD_STATUS tmrs on tmrs.id = tmr.status_id
join TM_ROAD_CATEGORY tmrc on tmrc.id = tmr.category_id
where tmr.deleted_at is null
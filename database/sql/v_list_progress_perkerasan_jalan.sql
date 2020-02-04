create or replace view V_LIST_PROGRESS_PERKERASAN_JALAN as 
select company_name, tme.estate_name, tma.afdeling_name, tmb.block_name, tmrs.status_name, tmrc.category_name,
round(ifnull((select sum(length) from TR_ROAD_PAVEMENT_PROGRESS trpp where trpp.road_id = tmr.id ),0),2) as curr_progress,
concat(round(ifnull((select sum(length) from TR_ROAD_PAVEMENT_PROGRESS trpp where trpp.road_id = tmr.id ),0)/
tmr.total_length*100,2),'%') as progress,
tmr.total_length,
tmr.asset_code,
tmr.segment,
tmr.id, tmr.company_code, tmr.werks, tmr.afdeling_code, tmr.block_code, tmr.road_code, tmr.road_name, tmr.status_pekerasan, tmr.status_aktif, tmr.deleted_at, tmr.created_at, tmr.updated_at, tmr.estate_code, tmr.updated_by 
from TM_ROAD tmr 
join TM_COMPANY tmc on tmc.company_code = tmr.company_code
join TM_ESTATE tme on tme.werks = tmr.werks
join TM_AFDELING tma on tma.afdeling_code = tmr.afdeling_code and tma.werks = tmr.werks
join TM_BLOCK tmb on tmb.block_code = tmr.block_code and tmb.werks = tmr.werks
join TM_ROAD_STATUS tmrs on tmrs.id = tmr.status_id
join TM_ROAD_CATEGORY tmrc on tmrc.id = tmr.category_id
where tmr.deleted_at is null
and tmrs.status_name = 'PRODUKSI'
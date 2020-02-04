create or replace view V_ROAD as 
select company_name, tme.estate_name, tma.afdeling_name, tmb.block_name, tmrs.status_name, tmrc.category_name, 
(select total_length from TR_ROAD_LOG trl where trl.road_id = tmr.id order by id desc limit 1) as total_length,
(select asset_code from TR_ROAD_LOG trl where trl.road_id = tmr.id order by id desc limit 1) as asset_code,
(select segment from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1) as segment,
tmr.* from TM_ROAD tmr
join TM_COMPANY tmc on tmc.company_code = tmr.company_code
join TM_ESTATE tme on tme.werks = tmr.werks
join TM_AFDELING tma on tma.afdeling_code = tmr.afdeling_code and tma.werks = tmr.werks
join TM_BLOCK tmb on tmb.block_code = tmr.block_code and tmb.werks = tmr.werks
join TM_ROAD_STATUS tmrs on tmrs.id = (select status_id from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1)
join TM_ROAD_CATEGORY tmrc on tmrc.id = (select category_id from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1)
where tmrc.category_name not in ('JALAN AKSES', 'JALAN DESA', 'JALAN NEGARA') and tmr.deleted_at is null
union all
select company_name, tme.estate_name, '-', '-', tmrs.status_name, tmrc.category_name, 
(select total_length from TR_ROAD_LOG trl where trl.road_id = tmr.id order by id desc limit 1) as total_length,
(select asset_code from TR_ROAD_LOG trl where trl.road_id = tmr.id order by id desc limit 1) as asset_code,
(select segment from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1) as segment,
tmr.* from TM_ROAD tmr
join TM_COMPANY tmc on tmc.company_code = tmr.company_code
join TM_ESTATE tme on tme.werks = tmr.werks
join TM_ROAD_STATUS tmrs on tmrs.id = (select status_id from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1)
join TM_ROAD_CATEGORY tmrc on tmrc.id = (select category_id from TR_ROAD_STATUS trs where trs.road_id = tmr.id order by id desc limit 1)
where tmrc.category_name in ('JALAN AKSES', 'JALAN DESA', 'JALAN NEGARA') and tmr.deleted_at is null
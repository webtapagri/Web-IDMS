create or replace view V_BLOCK as
select company_name,afdeling_name,estate_name, TB.* from TM_BLOCK TB
join TM_AFDELING TA on TA.id = TB.afdeling_id
join TM_ESTATE TE on TE.id = TA.estate_id
join TM_COMPANY TC on TC.id = TE.company_id
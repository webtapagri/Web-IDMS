create or replace view V_AFDELING as
select company_name,estate_name, TA.* from TM_AFDELING TA
join TM_ESTATE TE on TE.id = TA.estate_id
join TM_COMPANY TC on TC.id = TE.company_id
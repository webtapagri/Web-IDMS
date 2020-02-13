create or replace view V_ESTATE as
select company_name, TE.* from TM_ESTATE TE
join TM_COMPANY TC on TC.id = TE.company_id
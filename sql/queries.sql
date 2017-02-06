-- select normal data
select * 
  from weather_entries we1 
 where creation_dt IN ( select max(creation_dt) from weather_entries we2 where we2.resort = we1.resort and we2.date_info = we1.date_info)
   and date_info between date(NOW()) and date(now())+3
 order by snow desc


-- select total snow amount for next 4 days
select resort, region, sum(snow) as total_snow
  from weather_entries we1 
 where creation_dt IN ( select max(creation_dt) from weather_entries we2 where we2.resort = we1.resort and we2.date_info = we1.date_info)
   and date_info between date(NOW()) and date(now())+3
 group by resort, region
 order by total_snow desc

-- select snow values for resort
select resort, date_info, snow
  from weather_entries we1 
 where creation_dt IN ( select max(creation_dt) from weather_entries we2 where we2.resort = we1.resort and we2.date_info = we1.date_info)
   and date_info >= date(now())
   and resort like 'kitz%'
 order by date_info asc 

-- last update
select max(creation_dt) from weather_entries 
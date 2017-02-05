-- select normal data
select * 
  from weather_entries we1 
 where resort = 'tux' 
   and creation_dt IN ( select max(creation_dt) from weather_entries we2 where we2.resort = we1.resort and we2.date_info = we1.date_info)

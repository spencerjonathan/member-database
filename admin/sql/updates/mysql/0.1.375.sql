update #__md_member 
inner join #__users u on u.username = "Jon"
set insurance_group = "25-69", mod_date = current_timestamp(), mod_user_id = u.id
where #__md_member.insurance_group = "16-70";

update #__md_member 
inner join #__users u on u.username = "Jon"
set insurance_group = "70-79", mod_date = current_timestamp(), mod_user_id = u.id
where #__md_member.insurance_group = "Over 70";


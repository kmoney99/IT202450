SELECT * FROM Survey where title like CONCAT('%', :survey, '%')
SELECT * FROM Question where question like CONCAT('%', :question, '%') order by ASC
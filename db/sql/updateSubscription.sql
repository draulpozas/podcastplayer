UPDATE `podcasts`.`subscription` 
SET 
    `user_id` = '{{user_id}}', 
    `name` = '{{name}}', 
    `feed` = '{{feed}}' 
WHERE
    (`id` = '{{id}}');

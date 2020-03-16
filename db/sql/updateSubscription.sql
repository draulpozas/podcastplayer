UPDATE `podcasts`.`subscription` 
SET 
    `user_id` = '{{user_id}}', 
    `feed` = '{{feed}}' 
WHERE
    (`id` = '{{id}}');

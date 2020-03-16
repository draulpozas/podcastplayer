UPDATE `podcasts`.`user` 
SET 
    `username` = '{{username}}', 
    `passwd` = '{{passwd}}', 
    `email` = '{{email}}' 
WHERE
    (`id` = '{{id}}');

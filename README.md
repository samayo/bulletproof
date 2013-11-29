BulletProof
=
Simple, ~safe, fast PHP file uploading script. 

````php
$BulletProof =  BulletProof::set(array('png', 'jpeg', 'gif', 'jpg'),
                                  array('max-width'=>150, 
                                  'max-height'=>150),
                                  array(3000),
                                  'pictures/'
);
````


````php
if($_FILES):
 BulletProof::upload($_FILES['logo'], 'simon');
endif;
````

Will indent/add docs soon

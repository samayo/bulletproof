## BulletProof
============
#### A Fast, Simple and Secure PHP image-uploading class.


You can upload any type of image file securely by following these two simple steps.
First call `BulletProof::options()` to set the file types, file max-width, max-size and directory where
you want the uploaded file to go in. Here is the example:
````php
BulletProof::set(array('png', 'jpeg', 'gif', 'jpg'),
                 array('max-width'=>150, 'max-height'=>150),
                 30000,
                 'pictures/'
               );
````
Voila! I hope the arguments made sense to you. If not:
````php
array('png', 'jpeg', 'gif', 'jpg'), // is the types of file you are willing to accept.
// BECAREFUL NOT TO ACCEPT EXECUTABLE FILES.
array('max-width'=>150, 'max-height'=>150), // is the max width/size of the image you can accept.
3000 // is the max file size.
'pictures/' // is the directory/folder where you want the files to be uploaded into. Make sure you create it
//before using it.
````

Ok, now after having placed that script in your php file. All you have to do now is call the upload method like this:
````php
BulletProof::upload($_FILES['profile_pic'], 'simon');
````
The first argument is the `$_FILES` array with its `name` attribute given in you HTML form. The second
argument is the name you want to give your newly uploaded file. If you don't specify a name for it,
a default unique Id will be given.

### Example
In Short, this is what you your entire script should look like .

````php
include_once 'BulletProof.php';

BulletProof::set(array('png', 'jpeg', 'gif', 'jpg'),
                 array('max-width'=>150, 'max-height'=>150),
                 30000,
                 'pictures/'
                );



if($_FILES){
   $upload =  BulletProof::upload($_FILES['profile_pic'], 'simon');

   if($upload !== true)
   {
    echo "ERROR: ".$upload;
   }
}
````

### Why Static ?
Because static methods are faster than objects (dynamic) classes. It's all about optimization.
Since UNIT testing is irrelevant subject here, I couldn't find any reason not to use them.

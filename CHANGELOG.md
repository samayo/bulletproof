# CHANGELOG


### 2.0.5
 - Add feature to get the mimetype before calling upload() method

### 2.0.4 
 - fix #60 (JSON throws error due to a forwardslash in error messages)

### 2.0.3
 - git went haywire, 2.0.3 had to be born

### 2.0.2
 - enabled checking for 'exif_imagetype' function.
 - added configurable array messages thanks to github.com/lordgiotto

### 2.0.1
- Removed method `remove()`

### 2.0.0
- Removed watermark, resize, crop functionality
- renamed moaar and added getters and setters
- changed license to MITgit
- added `getJson()` to get image info in json format
- enabled passing of the `$_FILES` array through the class constructor.
- wrote more unit tests
- class uses `\ArrayAccess` for intuitive file submit detection


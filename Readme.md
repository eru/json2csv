JSON2CSV
========

Description
-----------
Convert json to csv or csv to json.

Usage
-----
Convert json to csv

    $ php json2csv.php my_file.json

Convert csv to json

    $ php json2csv.php -r my_file.csv

This script will automatically write the resulting file as INPUT\_FILE\_NAME.[json|csv]

More info
---------
json to csv:

    $ cat my_file.json
    [
        {
            "title": "title1",
            "tag": [
                "tag1",
                "tag2"
            ],
            "object": {
                "key1": "value1",
                "key2": "value2"
            }
        },
        {
            "skipObject": "keys does not match."
        },
        {
            "title": "title2",
            "tag": [
                "tag3",
                "tag4"
            ],
            "object": {
                "key3": "value3",
                "key4": "value4"
            }
        }
    ]
    $ php json2csv.php my_file.json
    $ cat my_file.csv
    title,tag,object
    title1,"a:2:{i:0;s:4:""tag1"";i:1;s:4:""tag2"";}","a:2:{s:4:""key1"";s:6:""value1"";s:4:""key2"";s:6:""value2"";}"
    title2,"a:2:{i:0;s:4:""tag3"";i:1;s:4:""tag4"";}","a:2:{s:4:""key3"";s:6:""value3"";s:4:""key4"";s:6:""value4"";}"

* Array and object convert to php serialized value.
* When object keys does not match first object keys skip object.

csv to json:

    $ cat my_file.csv
    title,tag,object,remark
    title1,"a:2:{i:0;s:4:""tag1"";i:1;s:4:""tag2"";}","a:2:{s:4:""key1"";s:6:""value1"";s:4:""key2"";s:6:""value2"";}","remark text."
    title2,"a:2:{i:0;s:4:""tag3"";i:1;s:4:""tag4"";}","a:2:{s:4:""key3"";s:6:""value3"";s:4:""key4"";s:6:""value4"";}",
    $ php json2csv.php -r my_file.csv
    $ cat my_file.json
    [
        {
            "title": "title1",
            "tag": [
                "tag1",
                "tag2"
            ],
            "object": {
                "key1": "value1",
                "key2": "value2"
            },
            "remark": "remark text."
        },
        {
            "title": "title2",
            "tag": [
                "tag3",
                "tag4"
            ],
            "object": {
                "key3": "value3",
                "key4": "value4"
            },
            "remark": null
        }
    ]

* Empty string convert to null.

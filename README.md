Date Formatter
==============

Render dates and date ranges in a human-readable format, including proper microformats-2 markup.


Usage
-----

### Date Range with Time

```php
echo DateFormatter::format('2013-10-08T07:00:00-07:00', '2013-10-08T17:00:00-07:00');
```

outputs

```html
<time class="dt-start" datetime="2013-10-08T07:00:00-07:00">
  October 8, 2013 from 7:00am
</time> 
to 
<time class="dt-end" datetime="2013-10-08T17:00:00-07:00">
  5:00pm (-0700)
</time>
```

(whitespace added for readability)

which displays in a browser as

```
October 8, 2013 from 7:00am to 5:00pm (-0700)
```

### Date Range with No Time

```php
echo DateFormatter::format('2013-10-08', '2013-10-11');
```

outputs

```html
<time class="dt-start" datetime="2013-10-08">
  October 8
</time>
-
<time class="dt-end" datetime="2013-10-11">
  11, 2013
</time>
```

which displays in a browser as

```
October 8-11, 2013
```

Other Examples
--------------

This example shows how progressively more data is added to the output as the start and end dates have less in common with each other.

```php
echo DateFormatter::format('2013-09-03', '2013-09-08');
// September 3-8, 2013

echo DateFormatter::format('2013-09-28', '2013-10-03');
// September 28 through October 3, 2013

echo DateFormatter::format('2013-12-30', '2014-01-02');
// December 30, 2013 through January 2, 2014
```

Here are similar examples when the dates include times as well.

```php
echo DateFormatter::format('2013-10-08T07:00:00-07:00', '2013-10-08T17:00:00-07:00');
// October 8, 2013 from 7:00am to 5:00pm (-0700)

echo DateFormatter::format('2013-10-08T07:00:00-07:00', '2013-10-10T17:00:00-07:00');
// October 8, 2013 at 7:00am until Oct 10 at 5:00pm (-0700)

echo DateFormatter::format('2013-08-31T07:00:00-07:00', '2013-09-01T17:00:00-07:00');
// August 31, 2013 7:00am until September 1 at 5:00pm (-0700)

echo DateFormatter::format('2013-12-31T07:00:00-07:00', '2014-01-01T17:00:00-07:00');
// December 31, 2013 7:00am until January 1, 2014 5:00pm (-0700)
```


Tests
-----

Please see the [tests](tests/BasicTest.php) for more complete examples of different output formats.



Future Enhancements
-------------------

* Optionally also display the day of the week in date range output
* Option to use short month names instead of full names
* Make the parser more tolerant of other input formats

If you see other input or output formats you would like handled, please open an Issue with a description. Bonus points if you write it as a test case:

```php
  public function testDescriptionOfWhatYoureTesting() {
    $this->_testEquals('Final Text Output', 'start-date', 'end-date');
  }
```


License
-------

Copyright 2013 by Aaron Parecki

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

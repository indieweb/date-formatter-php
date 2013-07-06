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


Future Enhancements
-------------------

* Optionally also display the day of the week in date range output
* Option to use short month names instead of full names


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

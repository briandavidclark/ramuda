# λ ramuda

Functional programming helper library for PHP based on [Ramda.js](https://ramdajs.com/)

As far as I know, this is the most feature complete port of [Ramda.js](https://ramdajs.com/) for PHP with over 300 functions. Also, includes many functions from [Ramda Adjunct](https://char0n.github.io/ramda-adjunct/2.24.0/index.html) and [Ramda Extension](https://ramda-extension.firebaseapp.com/docs/).

In addition, where possible, some of the functions have improved capabilities, such as **filter** and **map** handling strings and objects as well as the usual arrays.

Requires PHP 5.6 or higher.

Usage example:

```php
use ramuda\R;

$users = [
   ['id'=>'45', 'fName'=>'Jane', 'lName'=>'Doe'],
   ['id'=>'22', 'fName'=>'John', 'lName'=>'Doe'],
   ['id'=>'99', 'fName'=>'John', 'lName'=>'Smith']
];

$listToSelect = R::pipe(
   R::filter(R::propEq('lName', 'Doe')),
   R::sortBy(R::prop('id')),
   R::map(function($x){
      return "<option value='{$x['id']}'>{$x['fName']} {$x['lName']}</option>";
   }),
   R::join(''),
   R::wrapWith(['<select>', '</select>'])
);

echo $listToSelect($users);
```
Produces the following string:
```html
<select>
    <option value="22">John Doe</option>
    <option value="45">Jane Doe</option>
</select>
```

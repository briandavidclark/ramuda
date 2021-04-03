<?php

	/**
	 * Based on Ramda v0.27 - https://ramdajs.com/docs/
	 * Based on Ramda Adjunct v2.24.0 - https://char0n.github.io/ramda-adjunct/2.24.0/RA.html
	 * Based on Ramda Extension v0.10.3 - https://ramda-extension.firebaseapp.com/docs/
	 * Curry functions based on phamda - https://github.com/mpajunen/phamda
	 *
	 * Requires a minimum of PHP 5.6
	 *
	 * @author Brian Clark
	 * @version 1.4
	 * @link https://github.com/briandavidclark/ramuda
	 * @since 2020-05-13
	 */

	namespace Ramuda {

		use Closure;
		use RecursiveArrayIterator;
		use RecursiveIteratorIterator;
		use ReflectionFunction;
		use stdClass;
		use Traversable;

		abstract class R{

			private static $placeholder;

			//<editor-fold desc="FUNCTION">

			/**
			 * Used as a placeholder when currying.
			 * @return Placeholder
			 */
			public static function _(){
				return static::$placeholder ?: (static::$placeholder = new Placeholder());
			}

			/*
			 * OMITTED
			 * reason: not needed
			 * https://ramdajs.com/docs/#addIndex
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#always
			 * @param mixed $x
			 * @return Closure
			 */
			public static function always($x = null){
				return function() use ($x){
					return $x;
				};
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#alwaysEmptyArray
			 * @return Closure
			 */
			public static function alwaysEmptyArray(){
				return function(){
					return [];
				};
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#alwaysEmptyObject
			 * @return Closure
			 */
			public static function alwaysEmptyObject(){
				return function(){
					return new stdClass();
				};
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#alwaysEmptyString
			 * @return Closure
			 */
			public static function alwaysEmptyString(){
				return function(){
					return '';
				};
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#alwaysNull
			 * @return Closure
			 */
			public static function alwaysNull(){
				return function(){
					return null;
				};
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#alwaysOne
			 * @return Closure
			 */
			public static function alwaysOne(){
				return function(){
					return 1;
				};
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#alwaysZero
			 * @return Closure
			 */
			public static function alwaysZero(){
				return function(){
					return 0;
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#ap
			 * @param callable[] $fArr
			 * @param mixed[] $vArr
			 * @return Closure
			 */
			public static function ap($fArr = null, $vArr = null){
				return static::curryN(2, function($fArr, $vArr){
					$results = [];

					foreach($fArr as $f){
						foreach($vArr as $v){
							array_push($results, $f($v));
						}
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#apply
			 * @param callable $f
			 * @param mixed[] $argArr
			 * @return Closure
			 */
			public static function apply($f = null, $argArr = null){
				return static::curryN(2, function($f, $argArr){
					return call_user_func_array($f, $argArr);
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP
			 * https://ramdajs.com/docs/#applySpec
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#applyTo
			 * @param mixed $x
			 * @param callable $f
			 * @return Closure
			 */
			public static function applyTo($x = null, $f = null){
				return static::curryN(2, function($x, $f){
					return $f($x);
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#ascend
			 * @param callable $f
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function ascend($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					$xx = $f($x);
					$yy = $f($y);

					if($xx < $yy){
						return -1;
					}
					elseif($xx > $yy){
						return 1;
					}

					return 0;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure if useful because of currying
			 * https://ramdajs.com/docs/#binary
			 */

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP
			 * https://ramdajs.com/docs/#bind
			 */

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP
			 * https://ramdajs.com/docs/#call
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#comparator
			 * @param callable $pred
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function comparator($pred = null, $x = null, $y = null){
				return static::curryN(3, function($pred, $x, $y){
					if($pred($x, $y)){
						return -1;
					}
					elseif($pred($y, $x)){
						return 1;
					}

					return 0;
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#compose
			 * @param callable[] ...$args
			 * @return Closure
			 */
			public static function compose(...$args){
				return function($val) use ($args){
					$argsRev = array_reverse($args);

					return array_reduce($argsRev, function($acc, $f){
						return $f($acc);
					}, $val);
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#composeWith
			 * @param callable $f
			 * @param callable[] $fArr
			 * @return Closure
			 */
			public static function composeWith($f = null, $fArr = null){
				return static::curryN(2, function($f, $fArr){
					return function($val) use ($f, $fArr){
						/** @var array $newFArr */
						$newFArr = static::reverse($fArr);

						return array_reduce($newFArr, function($acc, $fItem) use ($f){
							return $f($fItem, $acc);
						}, $val);
					};
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#construct
			 * @param string $type
			 * @return Closure
			 */
			public static function construct($type){
				return function(...$args) use ($type){
					return new $type(...$args);
				};
			}

			/*
			 * OMITTED
			 * reason: not sure if useful
			 * https://ramdajs.com/docs/#constructN
			 */

			/*
			 * OMITTED
			 * reason: not sure if useful
			 * https://ramdajs.com/docs/#converge
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#curry
			 * @param callable|null $f
			 * @return Closure
			 */
			public static function curry($f){
				$r = new ReflectionFunction($f);
				return static::curryN($r->getNumberOfParameters(), $f);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#curryN
			 * @param int|null $n
			 * @param callable|null $f
			 * @return Closure
			 */
			public static function curryN($n, $f){
				$curryNRec = function($recv) use ($n, $f, &$curryNRec){
					return function() use ($recv, $n, $f, &$curryNRec){
						$left = $n;
						$argsIdx = 0;
						$combined = array();
						$combinedIdx = 0;
						$args = func_get_args();

						while($combinedIdx < count($recv) || $argsIdx < count($args)){
							if($combinedIdx < count($recv)
								&& ($recv[$combinedIdx] !== static::_() || $argsIdx > count($args))){
								$result = $recv[$combinedIdx];
							}
							else{
								$result = $args[$argsIdx];
								$argsIdx += 1;
							}

							$combined[$combinedIdx] = $result;
							$combinedIdx += 1;

							if($result !== static::_()){
								$left -= 1;
							}
						}

						if($left <= 0){
							return call_user_func_array($f, $combined);
						}
						else{
							return $curryNRec($combined);
						}
					};
				};

				return $curryNRec([]);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#descend
			 * @param callable $f
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function descend($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					$xx = $f($x);
					$yy = $f($y);

					if($xx > $yy){
						return -1;
					}
					elseif($xx < $yy){
						return 1;
					}

					return 0;
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#empty
			 * @param mixed $x
			 * @return Closure
			 */
			public static function emptyVal($x = null){
				return static::curryN(1, function($x){
					$type = static::type($x);

					if($type === 'array'){
						return [];
					}
					elseif($type === 'object'){
						return new stdClass();
					}
					elseif($type === 'string'){
						return '';
					}
					elseif($type === 'boolean'){
						return false;
					}
					elseif($type === 'double' || $type === 'integer'){
						return 0;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#F
			 * @return Closure
			 */
			public static function F(){
				return function(){
					return false;
				};
			}

			/*
			 * OMITTED
			 * reason: not sure if useful
			 * https://ramdajs.com/docs/#flip
			 */

			/*
			 * OMITTED
			 * reason: not sure if useful
			 * https://ramdajs.com/docs/#invoker
			 */

			/**
			 * @internal Function
			 * Hooks into a pipeline.
			 * @param callable $f
			 * @param mixed $val
			 * @return Closure
			 */
			public static function hook($f = null, $val = null){
				return static::curryN(2, function($f, $val){
					return $f($val);
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#identity
			 * @param mixed $x
			 * @return Closure
			 */
			public static function identity($x = null){
				return static::curryN(1, function($x){
					return $x;
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#juxt
			 * @param callable[] $fArr
			 * @return Closure
			 */
			public static function juxt($fArr = null){
				return function(...$args) use ($fArr){
					return static::map(function($f) use ($args){
						return $f(...$args);
					}, $fArr);
				};
			}

			/*
			 * OMITTED
			 * reason: not sure if useful
			 * https://ramdajs.com/docs/#lift
			 */

			/*
			 * OMITTED
			 * reason: not sure if useful
			 * https://ramdajs.com/docs/#liftN
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#memoizeWith
			 * @param callable $keyF - must return an int or string value
			 * @param callable $f
			 * @param mixed $val
			 * @return Closure
			 */
			public static function memoizeWith($keyF = null, $f = null, $val = null){
				$cache = [];

				return static::curryN(3, function($keyF, $f, $val) use (&$cache){
					$key = $keyF($val);

					if(!array_key_exists($key, $cache)){
						$cache[$key] = $f($val);
					}

					return $cache[$key];
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure if useful because of currying
			 * https://ramdajs.com/docs/#nAry
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#nthArg
			 * @param int $index
			 * @return Closure
			 */
			public static function nthArg($index){
				return function(...$args) use ($index){
					return $args[$index];
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#o
			 * @param callable $f1
			 * @param callable $f2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function o($f1 = null, $f2 = null, $x = null){
				return static::curryN(3, function($f1, $f2, $x){
					return $f1($f2($x));
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#of
			 * @param int $x
			 * @return Closure
			 */
			public static function of($x = null){
				return static::curryN(1, function($x){
					return [$x];
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#once
			 * @param callable $f
			 * @return Closure
			 */
			public static function once($f){
				$called = false;
				$result = null;

				return function(...$args) use ($f, &$called, &$result){
					if($called === false){
						$called = true;
						$result = $f(...$args);
					}

					return $result;
				};
			}

			/*
			 * OMITTED
			 * reason: no Promises in PHP
			 * https://ramdajs.com/docs/#otherwise
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#partial
			 * @param callable $f
			 * @param mixed[] $argsArr
			 * @return Closure
			 */
			public static function partial($f, $argsArr){
				return function(...$args) use ($f, $argsArr){
					return $f(...array_merge($argsArr, $args));
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#partialRight
			 * @param callable $f
			 * @param mixed[] $argsArr
			 * @return Closure
			 */
			public static function partialRight($f, $argsArr){
				return function(...$args) use ($f, $argsArr){
					return $f(...array_merge($args, $argsArr));
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#pipe
			 * @param callable ...$args
			 * @return Closure
			 */
			public static function pipe(...$args){
				return function($val) use ($args){
					return array_reduce($args, function($acc, $f){
						return $f($acc);
					}, $val);
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#pipeWith
			 * @param callable $f
			 * @param callable[] $fArr
			 * @return Closure
			 */
			public static function pipeWith($f = null, $fArr = null){
				return static::curryN(2, function($f, $fArr){
					return function($val) use ($f, $fArr){
						return array_reduce($fArr, function($acc, $fItem) use ($f){
							return $f($fItem, $acc);
						}, $val);
					};
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#T
			 * @return Closure
			 */
			public static function T(){
				return function(){
					return true;
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#tap
			 * @param callable $f
			 * @param mixed $val
			 * @return Closure
			 */
			public static function tap($f = null, $val = null){
				return static::curryN(2, function($f, $val){
					$f($val);
					return $val;
				})(...func_get_args());
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#toggle
			 * @param mixed $val1
			 * @param mixed $val2
			 * @param mixed $val3
			 * @return Closure
			 */
			public static function toggle($val1 = null, $val2 = null, $val3 = null){
				return static::curryN(3, function($val1, $val2, $val3){
					if($val3 === $val1){
						return $val2;
					}
					elseif($val3 === $val2){
						return $val1;
					}

					return null;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: no Promises in PHP
			 * https://ramdajs.com/docs/#then
			 */

			/*
			 * OMITTED
			 * reason: not sure if possible
			 * https://ramdajs.com/docs/#thunkify
			 */

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#tryCatch
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#unapply
			 * @param callable $f
			 * @return Closure
			 */
			public static function unapply($f){
				return function(...$args) use ($f){
					return $f($args);
				};
			}

			/*
			 * OMITTED
			 * reason: not sure if useful because of currying
			 * https://ramdajs.com/docs/#unary
			 */

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#uncurryN
			 */

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#useWith
			 */

			//</editor-fold>

			//<editor-fold desc="LIST">

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#adjust
			 * @param int|string $index
			 * @param callable $f
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function adjust($index = null, $f = null, $arr = null){
				return static::curryN(3, function($index, $f, $arr){
					$copy = static::arrayClone($arr);
					$copy[$index] = $f($copy[$index]);
					return $copy;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#all
			 * @param callable $pred
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function all($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $item){
						if($pred($item) === false){
							return false;
						}
					}

					return true;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.allEqual
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function allEqual($arr = null){
				return static::curryN(1, function($arr){
					$val = static::head($arr);

					foreach($arr as $item){
						if($item !== $val){
							return false;
						}
					}

					return true;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.allEqualTo
			 * @param mixed $x
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function allEqualTo($x = null, $arr = null){
				return static::curryN(2, function($x, $arr){
					foreach($arr as $item){
						if($item !== $x){
							return false;
						}
					}

					return true;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.allUnique
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function allUnique($arr = null){
				return static::curryN(1, function($arr){
					/** @var array $u */
					$u = static::uniq($arr);
					return count($u) === count($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#any
			 * @param callable $pred
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function any($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $item){
						if($pred($item) === false){
							return true;
						}
					}

					return false;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#aperture
			 * @param int $size
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function aperture($size = null, $arr = null){
				return static::curryN(2, function($size, $arr){
					$len = count($arr);
					$result = [];

					for($n = 0; $n < $len; $n++){
						if(array_key_exists($n + $size - 1, $arr)){
							$nTuple = [];

							for($i = 0; $i < $size; $i++){
								array_push($nTuple, $arr[$n + $i]);
							}

							array_push($result, $nTuple);
						}
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#append
			 * @param mixed $val
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function append($val = null, $arr = null){
				return static::curryN(2, function($val, $arr){
					/** @var array $copy */
					$copy = static::arrayClone($arr);
					array_push($copy, $val);

					return $copy;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * Get clone of array.
			 * @param array $x
			 * @return Closure
			 */
			public static function arrayClone($x = null){
				return static::curryN(1, function($x){
					return array_merge([], $x);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#objOf
			 * @param string $key
			 * @param mixed $val
			 * @return Closure
			 */
			public static function arrayOf($key = null, $val = null){
				return static::curryN(2, function($key, $val){
					return [$key => $val];
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#chain
			 * @param callable $f
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function chain($f = null, $arr = null){
				return static::curryN(2, function($f, $arr){
					$flattened = [];

					foreach($arr as $index => $element){
						$result = $f($element, $index, $arr);

						if(is_array($result) || $result instanceof Traversable){
							foreach($result as $item){
								$flattened[] = $item;
							}
						}
						elseif($result !== null){
							$flattened[] = $result;
						}
					}

					return $flattened;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.compact
			 * @param array $arr
			 * @return Closure
			 */
			public static function compact($arr = null){
				return static::curryN(1, function($arr){
					return static::filter(function($x){
						return !!$x;
					}, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#concat
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function concat($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$xType = static::type($x);
					$yType = static::type($y);

					if($xType === $yType && $xType === 'string'){
						return $x . $y;
					}
					elseif($xType === $yType && $xType === 'array'){
						return array_merge($x, $y);
					}
					else{
						return null;
					}
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#containsAll
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function containsAll($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$p = static::pipe(static::difference($x), static::isEmpty());
					return $p($y);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#containsAny
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function containsAny($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$p = static::pipe(static::intersection($x), static::isEmpty(), static::not());
					return $p($y);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#containsNone
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function containsNone($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$p = static::pipe(static::intersection($x), static::isEmpty());
					return $p($y);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#concat
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function concatRight($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$xType = static::type($x);
					$yType = static::type($y);

					if($xType === $yType && $xType === 'string'){
						return $y . $x;
					}
					elseif($xType === $yType && $xType === 'array'){
						return array_merge($y, $x);
					}
					else{
						return null;
					}
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#drop
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function drop($count = null, $x = null){
				return static::curryN(2, function($count, $x){
					$type = static::type($x);

					if($type === 'string'){
						return substr($x, $count, strlen($x));
					}
					elseif($type === 'array'){
						return array_slice($x, $count);
					}
					else{
						return null;
					}
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropLast
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function dropLast($count = null, $x = null){
				return static::curryN(2, function($count, $x){
					$type = static::type($x);

					if($type === 'string'){
						return substr($x, 0, strlen($x) - $count);
					}
					elseif($type === 'array'){
						return array_slice($x, 0, $count + 1);
					}
					else{
						return null;
					}
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropLastWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function dropLastWhile($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);

					if($type === 'string'){
						$p = static::pipe('strrev', static::dropWhile($pred), 'strrev');
						return $p($x);
					}
					elseif($type === 'array'){
						$p = static::pipe(static::arrayClone(), 'array_reverse', static::dropWhile($pred), 'array_reverse');
						return $p($x);
					}
					else{
						return null;
					}
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropRepeats
			 * @param array $x
			 * @return Closure
			 */
			public static function dropRepeats($x = null){
				return static::curryN(1, function($x){
					$result = [];

					foreach($x as $val){
						if(end($result) !== $val){
							array_push($result, $val);
						}
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropRepeatsWith
			 * @param callable $pred
			 * @param array $x
			 * @return Closure
			 */
			public static function dropRepeatsWith($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$result = [static::head($x)];

					foreach($x as $val){
						$matches = $pred(end($result)) === $pred($val);

						if(!$matches){
							array_push($result, $val);
						}
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function dropWhile($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);

					$unsetTil = function($arr) use ($pred){
						foreach($arr as $key => $value){
							if($pred($value) === true){
								unset($arr[$key]);
							}
							else{
								break;
							}
						}

						return array_values($arr);
					};

					if($type === 'string'){
						$p = static::pipe(static::split(''), $unsetTil, static::join(''));
						return $p($x);
					}
					elseif($type === 'array'){
						$p = static::pipe(static::arrayClone(), $unsetTil);
						return $p($x);
					}
					else{
						return null;
					}
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#duplicate
			 * @param mixed $x
			 * @return Closure
			 */
			public static function duplicate($x = null){
				return static::curryN(1, function($x){
					return [$x, $x];
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#forEach
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function each($f = null, $arr = null){
				return static::curryN(2, function($f, $arr){
					foreach($arr as $val){
						$f($val);
					}

					return $arr;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#endsWith
			 * @param mixed $val
			 * @param string|array $x
			 * @return Closure
			 */
			public static function endsWith($val = null, $x = null){
				return static::curryN(2, function($val, $x){
					$type = static::type($x);

					if($type === 'string'){
						return substr($x, -1) === $val;
					}
					elseif($type === 'array'){
						return end($x) === $val;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#ensureArray
			 * @param mixed $x
			 * @return Closure
			 */
			public static function ensureArray($x = null){
				return static::curryN(1, function($x){
					return static::isArray($x) ? $x : [$x];
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#filter
			 * @param callable $pred
			 * @param string|array|object $x
			 * @return Closure
			 */
			public static function filter($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);

					if($type === 'string'){
						/**@var array $str */
						$str = static::split('', $x);
						$result = array_filter($str, $pred);

						return static::join('', $result);
					}
					elseif($type === 'array'){
						return array_filter($x, $pred);
					}
					elseif($type === 'object'){
						$result = new stdClass();

						foreach($x as $key => $value){
							if($pred($value) === true){
								$result->$key = $value;
							}
						}

						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#find
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function find($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $val){
						if($pred($val)){
							return $val;
						}
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * Returns array of items found at provided indexes.
			 * @param int[]|string[] $idx
			 * @param array $arr
			 * @return Closure
			 */
			public static function findAtIndexes($idx = null, $arr = null){
				return static::curryN(2, function($idx, $arr){
					return static::map(function($val) use ($arr){
						return $arr[$val];
					}, $idx);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#findIndex
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function findIndex($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					for($n = 0; $n < count($arr); $n++){
						if($pred($arr[$n]) === true){
							return $n;
						}
					}

					return -1;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#findLast
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function findLast($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					for($n = count($arr) - 1; $n >= 0; $n--){
						if($pred($arr[$n]) === true){
							return $arr[$n];
						}
					}

					return -1;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#findLastIndex
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function findLastIndex($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					for($n = count($arr) - 1; $n >= 0; $n--){
						if($pred($arr[$n]) === true){
							return $n;
						}
					}

					return -1;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#findNotNil
			 * @param array $arr
			 * @return Closure
			 */
			public static function findNotNil($arr = null){
				return static::curryN(1, function($arr){
					foreach($arr as $x){
						if(!static::isNil($x)){
							return $x;
						}
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#flatten
			 * @param array $arr
			 * @return Closure
			 */
			public static function flatten($arr = null){
				return static::curryN(1, function($arr){
					$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr));
					$result = [];

					foreach($it as $val){
						array_push($result, $val);
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#fromPairs
			 * @param array $arr
			 * @return Closure
			 */
			public static function fromPairs($arr = null){
				return static::curryN(1, function($arr){
					$result = new stdClass();

					foreach($arr as $item){
						$key = $item[0];
						$result->$key = $item[1];
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#groupBy
			 * @param callable $keyFunc
			 * @param mixed $x
			 * @return Closure
			 */
			public static function groupBy($keyFunc = null, $x = null){
				return static::curryN(2, function($keyFunc, $x){
					return static::reduce(function($acc, $val) use ($keyFunc){
						$key = $keyFunc($val);

						if(!property_exists($acc, $key)){
							$acc->$key = [];
						}

						array_push($acc->$key, $val);

						return $acc;
					}, new stdClass(), $x);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#groupWith
			 * @param callable $compareFunc
			 * @param array $arr
			 * @return Closure
			 */
			public static function groupWith($compareFunc = null, $arr = null){
				return static::curryN(2, function($compareFunc, $arr){
					$result = [];
					$index = 0;
					$length = count($arr);

					while($index < $length){
						$nextIndex = $index + 1;

						while($nextIndex < $length && $compareFunc($arr[$nextIndex - 1], $arr[$nextIndex])){
							$nextIndex += 1;
						}

						array_push($result, array_slice($arr, $index, $nextIndex - $index));
						$index = $nextIndex;
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#gtThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function gtThanLength($length = null, $arr = null){
				return static::curryN(2, function($length, $arr){
					return $length > count($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#gteThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function gteThanLength($length = null, $arr = null){
				return static::curryN(2, function($length, $arr){
					return $length >= count($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#head
			 * @param array $arr
			 * @return Closure
			 */
			public static function head($arr = null){
				return static::curryN(1, function($arr){
					return reset($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#includes
			 * @param mixed $searchFor
			 * @param string|array|object $x
			 * @return Closure
			 */
			public static function includes($searchFor = null, $x = null){
				return static::curryN(2, function($searchFor, $x){
					$type = static::type($x);

					if($type === 'string'){
						return strpos($x, $searchFor) !== false;
					}
					elseif($type === 'array'){
						return in_array($searchFor, $x);
					}
					elseif($type === 'object'){
						return property_exists($x, $searchFor);
					}
					else{
						return null;
					}
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#indexBy
			 * @param callable $keyFunc
			 * @param array $arr
			 * @return Closure
			 */
			public static function indexBy($keyFunc = null, $arr = null){
				return static::curryN(2, function($keyFunc, $arr){
					$result = new stdClass();

					foreach($arr as $val){
						$key = $keyFunc($val);
						$result->$key = $val;
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#indexOf
			 * @param int|string $idx
			 * @param array $arr
			 * @return Closure
			 */
			public static function indexOf($idx = null, $arr = null){
				return static::curryN(2, function($idx, $arr){
					for($n = 0; $n < count($arr); $n++){
						if($arr[$n] === $idx){
							return $n;
						}
					}

					return -1;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#init
			 * @param array $arr
			 * @return Closure
			 */
			public static function init($arr = null){
				return static::curryN(1, function($arr){
					return array_slice($arr, 0, -1);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#insert
			 * @param int $index
			 * @param mixed $val
			 * @param array $arr
			 * @return Closure
			 */
			public static function insert($index = null, $val = null, $arr = null){
				return static::curryN(3, function($index, $val, $arr){
					/**@var array $copy */
					$copy = static::arrayClone($arr);
					array_splice($copy, $index, 0, $val);

					return $copy;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#insertAll
			 * @param int $index
			 * @param mixed[] $valArr
			 * @param array $arr
			 * @return Closure
			 */
			public static function insertAll($index = null, $valArr = null, $arr = null){
				return static::curryN(3, function($index, $valArr, $arr){
					/** @var array $copy */
					$copy = static::arrayClone($arr);
					array_splice($copy, $index, 0, $valArr);

					return $copy;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#intersperse
			 * @param mixed $val
			 * @param array $arr
			 * @return Closure
			 */
			public static function intersperse($val = null, $arr = null){
				return static::curryN(2, function($val, $arr){
					$result = [];
					$len = count($arr);

					for($n = 0; $n < $len; $n++){
						array_push($result, $arr[$n]);

						if($n < $len - 1){
							array_push($result, $val);
						}
					}

					return $result;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#into
			 */

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#join
			 * @param string $on
			 * @param array $arr
			 * @return Closure
			 */
			public static function join($on = null, $arr = null){
				return static::curryN(2, function($on, $arr){
					return implode($on, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#last
			 * @param array $arr
			 * @return Closure
			 */
			public static function last($arr = null){
				return static::curryN(1, function($arr){
					return end($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#lastIndexOf
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function lastIndexOf($x = null, $arr = null){
				return static::curryN(2, function($x, $arr){
					for($n = count($arr); $n > 0; $n--){
						if($arr[$n] === $x){
							return $n;
						}
					}

					return -1;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#length
			 * @param string|array|object $x
			 * @return Closure
			 */
			public static function length($x = null){
				return static::curryN(1, function($x){
					$type = static::type($x);

					if($type === 'string'){
						return strlen($x);
					}
					elseif($type === 'array'){
						return count($x);
					}
					elseif($type === 'object'){
						/**@var array $keys */
						$keys = static::keys($x);
						return count($keys);
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthEq
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthEq($length = null, $x = null){
				return static::curryN(1, function($length, $x){
					$varLength = static::isString($x) ? strlen($x) : count($x);
					return $varLength === $length;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthGt
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthGt($length = null, $x = null){
				return static::curryN(1, function($length, $x){
					$varLength = static::isString($x) ? strlen($x) : count($x);
					return $varLength > $length;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthGte
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthGte($length = null, $x = null){
				return static::curryN(1, function($length, $x){
					$varLength = static::isString($x) ? strlen($x) : count($x);
					return $varLength >= $length;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthLt
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthLt($length = null, $x = null){
				return static::curryN(2, function($length, $x){
					$varLength = static::isString($x) ? strlen($x) : count($x);
					return $varLength < $length;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthLte
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthLte($length = null, $x = null){
				return static::curryN(2, function($length, $x){
					$varLength = static::isString($x) ? strlen($x) : count($x);
					return $varLength <= $length;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthNotEq
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthNotEq($length = null, $x = null){
				return static::curryN(2, function($length, $x){
					$varLength = static::isString($x) ? strlen($x) : count($x);
					return $varLength !== $length;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#ltThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function ltThanLength($length = null, $arr = null){
				return static::curryN(2, function($length, $arr){
					return $length < count($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#lteThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function lteThanLength($length = null, $arr = null){
				return static::curryN(2, function($length, $arr){
					return $length <= count($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#map
			 * @param callable $mapper
			 * @param array|object|string $x
			 * @return Closure
			 */
			public static function map($mapper = null, $x = null){
				return static::curryN(2, function($mapper, $x){
					$type = static::type($x);

					if($type === 'array'){
						$result = [];
						$index = 0;

						foreach($x as $key => $value){
							array_push($result, $mapper($value, $key, $index));
							$index++;
						}

						return $result;
					}
					elseif($type === 'object'){
						$result = new stdClass();
						$index = 0;

						foreach($x as $key => $value){
							$result->$key = $mapper($value, $key, $index);
							$index++;
						}

						return $result;
					}
					elseif($type === 'string'){
						/** @var array $strArr */
						$strArr = static::split('', $x);
						$result = [];
						$index = 0;

						foreach($strArr as $key => $value){
							array_push($result, $mapper($value, $key, $index));
							$index++;
						}

						return static::join('', $result);
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#mapIndexed
			 * @param callable $mapper
			 * @param array|object $x
			 * @return Closure
			 */
			public static function mapIndexed($mapper = null, $x = null){
				return static::curryN(2, function($mapper, $x){
					$type = static::type($x);

					if($type === 'array'){
						$result = [];

						for($n = 0; $n < count($x); $n++){
							array_push($result, $mapper($x[$n], $n));
						}

						return $result;
					}
					elseif($type === 'object'){
						$result = new stdClass();
						$idx = 0;

						foreach($x as $key => $value){
							$result->$key = $mapper($value, $idx);
							$idx++;
						}

						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#mapAccum
			 * @param callable $f
			 * @param mixed $acc
			 * @param array $arr
			 * @return Closure
			 */
			public static function mapAccum($f = null, $acc = null, $arr = null){
				return static::curryN(3, function($f, $acc, $arr){
					$index = 0;
					$length = count($arr);
					$tuple = $acc;
					$result = [];

					while($index < $length){
						$tuple = $f($tuple[0], $arr[$index]);
						$result[$index] = $tuple[1];
						$index += 1;
					}

					return [$tuple[0], $result];
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#mapAccumRight
			 * @param callable $f
			 * @param mixed $acc
			 * @param array $arr
			 * @return Closure
			 */
			public static function mapAccumRight($f = null, $acc = null, $arr = null){
				return static::curryN(3, function($f, $acc, $arr){
					$index = count($arr) - 1;
					$result = [];
					$tuple = [$acc];

					while($index >= 0){
						$tuple = $f($tuple[0], $arr[$index]);
						$result[$index] = $tuple[1];
						$index -= 1;
					}

					return [$tuple[0], static::reverse($result)];
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#mergeAll
			 * @param array $arr
			 * @return Closure
			 */
			public static function mergeAll($arr = null){
				return static::curryN(1, function($arr){
					$result = [];

					foreach($arr as $obj){
						$result = array_merge($result, (array)$obj);
					}

					return (object)$result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#move
			 * @param int $from
			 * @param int $to
			 * @param array $arr
			 * @return Closure
			 */
			public static function move($from = null, $to = null, $arr = null){
				return static::curryN(3, function($from, $to, $arr){
					/** @var array $result */
					$result = static::arrayClone($arr);
					$length = count($result);
					$val = array_splice($result, $from, 1)[0];

					if($to < 0){
						array_splice($result, $length + $to, 0, $val);
					}
					else{
						array_splice($result, $to, 0, $val);
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * Unlike "move", this can't move to a negative index.
			 * @param int $index
			 * @param int $count
			 * @param array $arr
			 * @return Closure
			 */
			public static function moveLeft($index = null, $count = null, $arr = null){
				return static::curryN(3, function($index, $count, $arr){
					$toInd = $index - $count;
					$newIndex = ($toInd < 0) ? 0 : $toInd;

					return static::move($index, $newIndex, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * Unlike "move", this can't move to an index beyond the final array index.
			 * @param int $index
			 * @param int $count
			 * @param array $arr
			 * @return Closure
			 */
			public static function moveRight($index = null, $count = null, $arr = null){
				return static::curryN(3, function($index, $count, $arr){
					$max = count($arr) - 1;
					$toInd = $index + $count;
					$newIndex = ($toInd > $max) ? $max : $toInd;

					return static::move($index, $newIndex, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#none
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function none($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $item){
						if($pred($item) === true){
							return false;
						}
					}

					return true;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.notAllUnique
			 * @param array $arr
			 * @return Closure
			 */
			public static function notAllUnique($arr = null){
				return static::curryN(1, function($arr){
					return !static::allUnique($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#nth
			 * @param int $index
			 * @param string|array $x
			 * @return Closure
			 */
			public static function nth($index = null, $x = null){
				return static::curryN(2, function($index, $x){
					$type = static::type($x);

					if($type === 'string'){
						$sub = substr($x, $index, 1);

						return ($sub === false) ? null : $sub;
					}
					elseif($type === 'array'){
						return ($index < 0) ? $x[$index + count($x)] : $x[$index];
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.omitIndexes
			 * @param int[] $indexes
			 * @param array $arr
			 * @return Closure
			 */
			public static function omitIndexes($indexes = null, $arr = null){
				return static::curryN(2, function($indexes, $arr){
					/** @var array $newArr */
					$newArr = static::arrayClone($arr);
					$arrLength = count($arr);

					foreach($indexes as $index){
						$i = ($index < 0) ? ($arrLength + $index) : $index;
						unset($newArr[$i]);
					}

					return array_values($newArr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#pair
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function pair($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return [$x, $y];
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#partition
			 * @param callable $pred
			 * @param array|object $x
			 * @return Closure
			 */
			public static function partition($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);

					if($type === 'object'){
						$trueObj = new stdClass();
						$falseObj = new stdClass();

						foreach($x as $key => $value){
							if($pred($value) === true){
								$trueObj->$key = $value;
							}
							else{
								$falseObj->$key = $value;
							}
						}

						return [$trueObj, $falseObj];
					}
					elseif($type === 'array'){
						$trueArr = [];
						$falseArr = [];

						foreach($x as $val){
							if($pred($val) === true){
								array_push($trueArr, $val);
							}
							else{
								array_push($falseArr, $val);
							}
						}

						return [$trueArr, $falseArr];
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.pickIndexes
			 * @param int[] $indexes
			 * @param array $arr
			 * @return Closure
			 */
			public static function pickIndexes($indexes = null, $arr = null){
				return static::curryN(2, function($indexes, $arr){
					$results = [];
					$arrLen = count($arr);

					foreach($indexes as $index){
						$i = ($index < 0) ? ($arrLen + $index) : $index;
						array_push($results, $arr[$i]);
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#pluck
			 * @param string $key
			 * @param mixed $x
			 * @return Closure
			 */
			public static function pluck($key = null, $x = null){
				return static::curryN(2, function($key, $x){
					return static::map(static::prop($key), $x);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#prepend
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function prepend($x = null, $arr = null){
				return static::curryN(2, function($x, $arr){
					/**@var array $copy */
					$copy = static::arrayClone($arr);
					array_unshift($copy, $x);
					return $copy;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#range
			 * @param int $x
			 * @param int $y
			 * @return Closure
			 */
			public static function range($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$results = [];

					for($n = $x; $n < $y; $n++){
						array_push($results, $n);
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reduce
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param array|object|string $x
			 * @return Closure
			 */
			public static function reduce($reducer = null, $defaultVal = null, $x = null){
				return static::curryN(3, function($reducer, $defaultVal, $x){
					$type = static::type($x);

					if($type === 'object' || $type === 'array' || $type === 'string'){
						$arr = ($type === 'string') ? str_split($x) : $x;
						$acc = $defaultVal;

						foreach($arr as $key => $value){
							$acc = $reducer($acc, $value, $key);
						}

						return $acc;
					}

					return null;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#reduced
			 */

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reduceBy
			 * @param callable $valueFunc
			 * @param mixed $defaultVal
			 * @param callable $keyFunc
			 * @param mixed $x
			 * @return Closure
			 */
			public static function reduceBy($valueFunc = null, $defaultVal = null, $keyFunc = null, $x = null){
				return static::curryN(4, function($valueFunc, $defaultVal, $keyFunc, $x){
					return static::reduce(function($acc, $val) use ($valueFunc, $defaultVal, $keyFunc){
						$key = $keyFunc($val);
						$acc->$key = $valueFunc(property_exists($acc, $key) ? $acc->$key : $defaultVal, $val);

						return $acc;
					}, new stdClass(), $x);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reduceRight
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param mixed $x
			 * @return Closure
			 */
			public static function reduceRight($reducer = null, $defaultVal = null, $x = null){
				return static::curryN(3, function($reducer, $defaultVal, $x){
					$type = static::type($x);
					$reducee = null;

					if($type === 'object'){
						$obj = clone $x;
						$reducee = (object)static::reverse((array)$obj);
					}
					elseif($type === 'array'){
						$reducee = static::reverse($x);
					}

					return static::reduce($reducer, $defaultVal, $reducee);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reduceWhile
			 * @param callable $pred - Takes acc and val args.
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param mixed $x
			 * @return Closure
			 */
			public static function reduceWhile($pred = null, $reducer = null, $defaultVal = null, $x = null){
				return static::curryN(4, function($pred, $reducer, $defaultVal, $x){
					$type = static::type($x);

					if($type === 'object'){
						$objAcc = $defaultVal;

						foreach($x as $key => $value){
							if($pred($objAcc, $value) === false){
								break;
							}

							$objAcc = $reducer($objAcc, $value);
						}

						return $objAcc;
					}
					elseif($type === 'array'){
						$arrAcc = $defaultVal;

						foreach($x as $val){
							if($pred($arrAcc, $val) === false){
								break;
							}

							$arrAcc = $reducer($arrAcc, $val);
						}

						return $arrAcc;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reject
			 * @param callable $pred
			 * @param mixed $x
			 * @return Closure
			 */
			public static function reject($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$newPred = static::pipe($pred, static::not());
					return static::filter($newPred, $x);
				})(...func_get_args());
			}

			/**
			 * Only applies to indexed arrays. Added because, in PHP, "array_filter" returns array with keys removed.
			 * @internal List
			 * @param array $arr
			 * @return Closure
			 */
			public static function reindex($arr = null){
				return static::curryN(1, function($arr){
					return array_values($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#rejectEq
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function rejectEq($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return static::filter(function($z) use ($x){
						return $z !== $x;
					}, $y);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#rejectNil
			 * @param mixed $x
			 * @return Closure
			 */
			public static function rejectNil($x = null){
				return static::curryN(1, function($x){
					return static::filter(function($y) use ($x){
						return !static::isNil($y);
					}, $x);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#remove
			 * @param int $start
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function remove($start = null, $count = null, $x = null){
				return static::curryN(3, function($start, $count, $x){
					$type = static::type($x);

					if($type === 'array'){
						/** @var array $copy */
						$copy = static::arrayClone($x);
						array_splice($copy, $start, $count);

						return $copy;
					}
					elseif($type === 'string'){
						/** @var array $strArr */
						$strArr = static::split('', $x);
						array_splice($strArr, $start, $count);

						return static::join('', $strArr);
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#repeat
			 * @param mixed $val
			 * @param int $count
			 * @return Closure
			 */
			public static function repeat($val = null, $count = null){
				return static::curryN(2, function($val, $count){
					return array_fill(0, $count, $val);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reverse
			 * @param array $arr
			 * @return Closure
			 */
			public static function reverse($arr = null){
				return static::curryN(1, function($arr){
					$p = static::pipe(static::arrayClone(), 'array_reverse');
					return $p($arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#scan
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param array $arr
			 * @return Closure
			 */
			public static function scan($reducer = null, $defaultVal = null, $arr = null){
				return static::curryN(3, function($reducer, $defaultVal, $arr){
					$index = 0;
					$length = count($arr);
					$result = [$defaultVal];

					while($index < $length){
						$defaultVal = $reducer($defaultVal, $arr[$index]);
						$result[$index + 1] = $defaultVal;
						$index += 1;
					}

					return $result;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: Monad functions beyond the scope of this library
			 * https://ramdajs.com/docs/#sequence
			 */

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#slice
			 * @param int $from
			 * @param int $to
			 * @param string|array $x
			 * @return Closure
			 */
			public static function slice($from = null, $to = null, $x = null){
				return static::curryN(3, function($from, $to, $x){
					$type = static::type($x);

					if($type === 'array'){
						return array_slice($x, $from, $to);
					}
					elseif($type === 'string'){
						$sub = substr($x, $from, $to);
						return $sub === false ? '' : $sub;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#sort
			 * @param callable $sorter
			 * @param array $arr
			 * @return Closure
			 */
			public static function sort($sorter = null, $arr = null){
				return static::curryN(2, function($sorter, $arr){
					/**@var array $y */
					$y = static::arrayClone($arr);
					usort($y, $sorter);
					return $y;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * Sorts a list of numbers from low to high.
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortNumAsc($arr = null){
				return static::curryN(1, function($arr){
					return static::sort(function($a, $b){
						if($a == $b){
							return 0;
						}

						return ($a < $b) ? -1 : 1;
					}, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * Sorts a list of numbers from high to low.
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortNumDes($arr = null){
				return static::curryN(1, function($arr){
					return static::sort(function($a, $b){
						if($a == $b){
							return 0;
						}

						return ($a < $b) ? 1 : -1;
					}, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#splitAt
			 * @param int $index
			 * @param string|array $x
			 * @return Closure
			 */
			public static function splitAt($index = null, $x = null){
				return static::curryN(2, function($index, $x){
					$type = static::type($x);

					if($type === 'array'){
						return [array_slice($x, 0, $index), array_slice($x, $index, count($x))];
					}
					elseif($type === 'string'){
						$sub1 = substr($x, 0, $index);
						$sub2 = substr($x, $index, strlen($x));
						$sub1 = ($sub1 === false) ? '' : $sub1;
						$sub2 = ($sub2 === false) ? '' : $sub2;

						return [$sub1, $sub2];
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#splitEvery
			 * @param int $length
			 * @param string|array $x
			 * @return Closure
			 */
			public static function splitEvery($length = null, $x = null){
				return static::curryN(2, function($length, $x){
					$type = static::type($x);

					if($type !== 'array' && $type !== 'string'){
						return null;
					}

					$result = [];
					$idx = 0;
					$count = $type === 'array' ? count($x) : strlen($x);

					while($idx < $count){
						if($type === 'array'){
							array_push($result, array_slice($x, $idx, $length));
						}
						elseif($type === 'string'){
							$sub = substr($x, $idx, $length);
							$sub = ($sub === false) ? '' : $sub;
							array_push($result, $sub);
						}

						$idx += $length;
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#splitWhen
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function splitWhen($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);
					/**@var array $arr */
					$arr = $x;

					if($type === 'string'){
						$arr = static::split('', $x);
					}

					if($type === 'array' || $type === 'string'){
						for($n = 0; $n < count($arr); $n++){
							if($pred($arr[$n]) === true){
								return static::splitAt($n, $arr);
							}
						}
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#startsWith
			 * @param array|string $prefix
			 * @param string|array $x
			 * @return Closure
			 */
			public static function startsWith($prefix = null, $x = null){
				return static::curryN(2, function($prefix, $x){
					$type = static::type($x);

					if($type === 'string'){
						$len = strlen($prefix);
						return static::equals($prefix, static::take($len, $x));
					}
					elseif($type === 'array'){
						$len = count($prefix);
						return static::equals($prefix, static::take($len, $x));
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#tail
			 * @param string|array $x
			 * @return Closure
			 */
			public static function tail($x = null){
				return static::curryN(1, function($x){
					$type = static::type($x);

					if($type === 'string'){
						return static::slice(1, strlen($x), $x);
					}
					elseif($type === 'array'){
						return array_slice($x, 1);
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#take
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function take($count = null, $x = null){
				return static::curryN(2, function($count, $x){
					$type = static::type($x);

					if($type === 'string'){
						return substr($x, 0, $count);
					}
					elseif($type === 'array'){
						return array_slice($x, 0, $count);
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#takeLast
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function takeLast($count = null, $x = null){
				return static::curryN(2, function($count, $x){
					$type = static::type($x);

					if($type === 'string'){
						return substr($x, 0 - $count, $count);
					}
					elseif($type === 'array'){
						return array_slice($x, 0 - $count, $count);
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#takeLastWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function takeLastWhile($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);
					/**@var array $y */
					$y = ($type === 'string') ? static::split('', $x) : $x;

					if($type === 'array' || $type === 'string'){
						$results = [];

						for($n = count($y) - 1; $n > 0; $n--){
							if($pred($y[$n]) === true){
								array_push($results, $y[$n]);
							}
							else{
								break;
							}
						}

						$results = static::reverse($results);
						return ($type === 'string') ? static::join('', $results) : $results;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#takeWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function takeWhile($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);
					/** @var array|string $y */
					$y = ($type === 'string') ? static::split('', $x) : $x;

					if($type === 'array' || $type === 'string'){
						$results = [];

						for($n = 0; $n < count($y); $n++){
							if($pred($y[$n]) === true){
								array_push($results, $y[$n]);
							}
							else{
								break;
							}
						}

						return ($type === 'string') ? static::join('', $results) : $results;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#times
			 * @param callable $f
			 * @param int $count
			 * @return Closure
			 */
			public static function times($f = null, $count = null){
				return static::curryN(2, function($f, $count){
					$result = [];

					for($n = 0; $n < $count; $n++){
						array_push($result, $f($n));
					}

					return $result;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#transduce
			 */

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#transpose
			 * @param array $arr
			 * @return Closure
			 */
			public static function transpose($arr = null){
				return static::curryN(1, function($arr){
					$i = 0;
					$result = [];

					while($i < count($arr)){
						$innerArr = $arr[$i];
						$j = 0;

						while($j < count($innerArr)){
							if($result[$j] === null){
								$result[$j] = [];
							}

							array_push($result[$j], $innerArr[$j]);
							$j += 1;
						}

						$i += 1;
					}

					return $result;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: Monad functions beyond the scope of this library
			 * https://ramdajs.com/docs/#traverse
			 */

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#unfold
			 * @param callable $f
			 * @param mixed $seed
			 * @return Closure
			 */
			public static function unfold($f = null, $seed = null){
				return static::curryN(2, function($f, $seed){
					$pair = $f($seed);
					$result = [];

					while($pair && count($pair)){
						$result[count($result)] = $pair[0];
						$pair = $f($pair[1]);
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#uniq
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniq($arr = null){
				return static::curryN(1, function($arr){
					return array_values(array_unique($arr));
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#uniqAppend
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqAppend($x = null, $arr = null){
				return static::curryN(2, function($x, $arr){
					return static::uniq(static::append($x, $arr));
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#uniqBy
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqBy($f = null, $arr = null){
				return static::curryN(2, function($f, $arr){
					$set = [];
					$result = [];

					foreach($arr as $val){
						$applied = $f($val);

						if(!in_array($val, $result, true) && !in_array($applied, $set, true)){
							array_push($set, $applied);
							array_push($result, $val);
						}
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#uniqPrepend
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqPrepend($x = null, $arr = null){
				return static::curryN(2, function($x, $arr){
					return static::uniq(static::prepend($x, $arr));
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#uniqWith
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqWith($pred = null, $arr = null){
				return static::curryN(2, function($pred, $arr){
					$result = [];

					foreach($arr as $val){
						$hasIndex = false;

						foreach($result as $subVal){
							if($pred($val, $subVal) === true){
								$hasIndex = true;
								break;
							}
						}

						if($hasIndex === false){
							array_push($result, $val);
						}
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#unnest
			 * @param array $arr
			 * @return Closure
			 */
			public static function unnest($arr = null){
				return static::curryN(1, function($arr){
					return static::chain(static::identity(), $arr);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#update
			 * @param int $index ;
			 * @param mixed $val ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function update($index = null, $val = null, $arr = null){
				return static::curryN(3, function($index, $val, $arr){
					$copy = static::arrayClone($arr);
					$copy[$index] = $val;

					return $copy;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#without
			 * @param mixed[] $values ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function without($values = null, $arr = null){
				return static::curryN(2, function($values, $arr){
					$results = [];

					foreach($arr as $item){
						if(!in_array($item, $values, true)){
							array_push($results, $item);
						}
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#xPairs
			 * @param mixed $x ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function xPairs($x = null, $arr = null){
				return static::curryN(2, function($x, $arr){
					$results = [];

					foreach($arr as $val){
						array_push($results, [$x, $val]);
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#xPairsRight
			 * @param mixed $x ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function xPairsRight($x = null, $arr = null){
				return static::curryN(2, function($x, $arr){
					$results = [];

					foreach($arr as $val){
						array_push($results, [$val, $x]);
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#xprod
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function xprod($arr1 = null, $arr2 = null){
				return static::curryN(2, function($arr1, $arr2){
					$idx = 0;
					$iLen = count($arr1);
					$j = null;
					$jLen = count($arr2);
					$result = [];

					while($idx < $iLen){
						$j = 0;

						while($j < $jLen){
							$result[count($result)] = [$arr1[$idx], $arr2[$j]];
							$j += 1;
						}

						$idx += 1;
					}

					return $result;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#zip
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function zip($arr1 = null, $arr2 = null){
				return static::curryN(2, function($arr1, $arr2){
					$rv = [];
					$idx = 0;
					$len = min(count($arr1), count($arr2));

					while($idx < $len){
						$rv[$idx] = [$arr1[$idx], $arr2[$idx]];
						$idx += 1;
					}

					return $rv;
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#zipObj
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function zipObj($arr1 = null, $arr2 = null){
				return static::curryN(2, function($arr1, $arr2){
					$p = static::pipe(static::zip($arr2), static::fromPairs());
					return $p($arr1);
				})(...func_get_args());
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#zipWith
			 * @param callable $f
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function zipWith($f = null, $arr1 = null, $arr2 = null){
				return static::curryN(3, function($f, $arr1, $arr2){
					$rv = [];
					$idx = 0;
					$len = min(count($arr1), count($arr2));

					while($idx < $len){
						$rv[$idx] = $f($arr1[$idx], $arr2[$idx]);
						$idx += 1;
					}

					return $rv;
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="LOGGING">

			/**
			 * @internal Logging
			 * @param mixed $x
			 * @return Closure
			 */
			public static function dumpVal($x = null){
				return static::curryN(1, function($x){
					return static::tap(function($x){
						var_dump($x);
					}, $x);
				})(...func_get_args());
			}

			/**
			 * @internal Logging
			 * @param mixed $x
			 * @return Closure
			 */
			public static function echoVal($x = null){
				return static::curryN(1, function($x){
					return static::tap(function($x){
						echo $x;
					}, $x);
				})(...func_get_args());
			}

			/**
			 * @internal Logging
			 * @param mixed $x
			 * @return Closure
			 */
			public static function printVal($x = null){
				return static::curryN(1, function($x){
					return static::tap(function($x){
						print_r($x);
					}, $x);
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="LOGIC">

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#allPass
			 * @param callable[] $preds
			 * @param mixed $x
			 * @return Closure
			 */
			public static function allPass($preds = null, $x = null){
				return static::curryN(2, function($preds, $x){
					foreach($preds as $pred){
						if($pred($x) === false){
							return false;
						}
					}

					return true;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#and
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function andTrue($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y == true && $x == true;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#anyPass
			 * @param callable[] $preds
			 * @param mixed $x
			 * @return Closure
			 */
			public static function anyPass($preds = null, $x = null){
				return static::curryN(2, function($preds, $x){
					foreach($preds as $pred){
						if($pred($x) === true){
							return true;
						}
					}

					return false;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#both
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function both($pred1 = null, $pred2 = null, $x = null){
				return static::curryN(3, function($pred1, $pred2, $x){
					return $pred1($x) && $pred2($x);
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#complement
			 * @param callable $pred
			 * @param mixed $x
			 * @return Closure
			 */
			public static function complement($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					return !$pred($x);
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#cond
			 * @param callable[] ...$args
			 * @return Closure
			 */
			public static function cond(...$args){
				return function($val) use ($args){
					return array_reduce($args, function($acc, $fArr) use ($val){
						return ($acc === null && $fArr[0]($val) === true) ? $fArr[1]($val) : $acc;
					}, null);
				};
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#defaultTo
			 * @param mixed $val
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultTo($val = null, $x = null){
				return static::curryN(2, function($val, $x){
					return isset($x) ? $x : $val;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToEmptyArray
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToEmptyArray($x = null){
				return static::curryN(1, function($x){
					return isset($x) ? $x : [];
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToEmptyObject
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToEmptyObject($x = null){
				return static::curryN(1, function($x){
					return isset($x) ? $x : new stdClass();
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToEmptyString
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToEmptyString($x = null){
				return static::curryN(1, function($x){
					return isset($x) ? $x : '';
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToFalse
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToFalse($x = null){
				return static::curryN(1, function($x){
					return isset($x) ? $x : false;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToOne
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToOne($x = null){
				return static::curryN(1, function($x){
					return isset($x) ? $x : 1;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToOne
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToTrue($x = null){
				return static::curryN(1, function($x){
					return isset($x) ? $x : true;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToZero
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToZero($x = null){
				return static::curryN(1, function($x){
					return isset($x) ? $x : 0;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.defaultWhen
			 * @param callable $pred
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function defaultWhen($pred = null, $x = null, $y = null){
				return static::curryN(3, function($pred, $x, $y){
					return $pred($y) === true ? $x : $y;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#either
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function either($pred1 = null, $pred2 = null, $x = null){
				return static::curryN(3, function($pred1, $pred2, $x){
					return $pred1($x) || $pred2($x);
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#ifElse
			 * @param callable $pred
			 * @param callable $ifTrue
			 * @param callable $ifFalse
			 * @param mixed $x
			 * @return Closure
			 */
			public static function ifElse($pred = null, $ifTrue = null, $ifFalse = null, $x = null){
				return static::curryN(4, function($pred, $ifTrue, $ifFalse, $x){
					return ($pred($x) === true) ? $ifTrue($x) : $ifFalse($x);
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#isEmpty
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isEmpty($x = null){
				return static::curryN(1, function($x){
					return empty($x);
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#isFalsy
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isFalsy($x = null){
				return static::curryN(1, function($x){
					return !(boolean)$x;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#isTruthy
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isTruthy($x = null){
				return static::curryN(1, function($x){
					return (boolean)$x;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.neither
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function neither($pred1 = null, $pred2 = null, $x = null){
				return static::curryN(3, function($pred1, $pred2, $x){
					return !($pred1($x) || $pred2($x));
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.nonePass
			 * @param callable[] $preds
			 * @param mixed $x
			 * @return Closure
			 */
			public static function nonePass($preds = null, $x = null){
				return static::curryN(2, function($preds, $x){
					foreach($preds as $f){
						if($f($x) === true){
							return false;
						}
					}

					return true;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.nor
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function nor($pred1 = null, $pred2 = null, $x = null){
				return static::curryN(3, function($pred1, $pred2, $x){
					return $pred1($x) === false && $pred2($x) === false;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#not
			 * @param mixed $x
			 * @return Closure
			 */
			public static function not($x = null){
				return static::curryN(1, function($x){
					return !$x;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#notEqual
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function notEqual($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $x !== $y;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#or
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function orEither($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $x || $y;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#pathSatisfies
			 * @param callable $pred
			 * @param string[] $keys
			 * @param array|object $x
			 * @return Closure
			 */
			public static function pathSatisfies($pred = null, $keys = null, $x = null){
				return static::curryN(3, function($pred, $keys, $x){
					$ifElse = static::ifElse($pred, static::always(true), static::always(false));
					$p = static::pipe(static::path($keys), $ifElse);
					return $p($x);
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#propSatisfies
			 * @param callable $pred
			 * @param string $key
			 * @param array|object $x
			 * @return Closure
			 */
			public static function propSatisfies($pred = null, $key = null, $x = null){
				return static::curryN(3, function($pred, $key, $x){
					return $pred(static::prop($key, $x)) ? true : false;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#unless
			 * @param callable $pred
			 * @param callable $ifFalse
			 * @param mixed $x
			 * @return Closure
			 */
			public static function unless($pred = null, $ifFalse = null, $x = null){
				return static::curryN(3, function($pred, $ifFalse, $x){
					return $pred($x) ? $x : $ifFalse($x);
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#until
			 * @param callable $pred
			 * @param callable $do
			 * @param mixed $x
			 * @return Closure
			 */
			public static function until($pred = null, $do = null, $x = null){
				return static::curryN(3, function($pred, $do, $x){
					$val = $x;

					while(!$pred($val)){
						$val = $do($val);
					}

					return $val;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#when
			 * @param callable $pred
			 * @param callable $ifTrue
			 * @param mixed $x
			 * @return Closure
			 */
			public static function when($pred = null, $ifTrue = null, $x = null){
				return static::curryN(3, function($pred, $ifTrue, $x){
					return $pred($x) ? $ifTrue($x) : $x;
				})(...func_get_args());
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#xor
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function xorOp($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return (bool)($x xor $y);
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="MATH">

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.ceil
			 * @param int|float $x
			 * @return Closure
			 */
			public static function ceil($x = null){
				return static::curryN(1, function($x){
					return ceil($x);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#add
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function add($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $x + $y;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#dec
			 * @param int|float $x
			 * @return Closure
			 */
			public static function dec($x = null){
				return static::curryN(1, function($x){
					return $x - 1;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#divide
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function divide($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y / $x;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.floor
			 * @param int|float $x
			 * @return Closure
			 */
			public static function floor($x = null){
				return static::curryN(1, function($x){
					return floor($x);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#inc
			 * @param int|float $x
			 * @return Closure
			 */
			public static function inc($x = null){
				return static::curryN(1, function($x){
					return $x + 1;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#mean
			 * @param int[]|float[] $x
			 * @return Closure
			 */
			public static function mean($x = null){
				return static::curryN(1, function($x){
					$p = static::pipe(static::sum(), static::divide(count($x)));
					return $p($x);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#median
			 * @param int[]|float[] $x
			 * @return Closure
			 */
			public static function median($x = null){
				return static::curryN(1, function($x){
					$p = static::pipe(static::arrayClone(), static::sortNumAsc());
					$y = $p($x);
					$len = count($y);

					if($len === 0){
						return null;
					}

					$width = 2 - $len % 2;
					$idx = ($len - $width) / 2;
					$z = array_slice($y, $idx, $width);

					return static::mean($z);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#modulo
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function modulo($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y % $x;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#multiply
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function multiply($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $x * $y;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#negate
			 * @param float|int $x
			 * @return Closure
			 */
			public static function negate($x = null){
				return static::curryN(1, function($x){
					return $x * -1;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * y to the power of x.
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function pow($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return pow($y, $x);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#product
			 * @param float[]|int[] $x
			 * @return Closure
			 */
			public static function product($x = null){
				return static::curryN(1, function($x){
					return R::reduce(function($acc, $num){
						return $acc * $num;
					}, 1, $x);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.round
			 * @param float|int $x
			 * @return Closure
			 */
			public static function round($x = null){
				return static::curryN(1, function($x){
					return round($x);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.sign
			 * @param float|int $x
			 * @return Closure
			 */
			public static function sign($x = null){
				return static::curryN(1, function($x){
					if($x < 0){
						return -1;
					}
					elseif($x > 0){
						return 1;
					}

					return 0;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#subtract
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function subtract($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $x - $y;
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#sum
			 * @param float[]|int[] $x
			 * @return Closure
			 */
			public static function sum($x = null){
				return static::curryN(1, function($x){
					return R::reduce(function($acc, $num){
						return $acc + $num;
					}, 0, $x);
				})(...func_get_args());
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.trunc
			 * @param mixed $x
			 * @return Closure
			 */
			public static function trunc($x = null){
				return static::curryN(1, function($x){
					return (int)floor($x);
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="OBJECT">

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#assoc
			 * @param string $key
			 * @param string $val
			 * @param object|array $x
			 * @return Closure
			 */
			public static function assoc($key = null, $val = null, $x = null){
				return static::curryN(3, function($key, $val, $x){
					$type = static::type($x);

					if($type === 'array'){
						$result = static::arrayClone($x);
						$result[$key] = $val;
						return $result;
					}
					elseif($type === 'object'){
						$result = clone $x;
						$result->$key = $val;
						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#assocPath
			 * @param string[] $keys
			 * @param string $val
			 * @param object|array $x
			 * @return Closure
			 */
			public static function assocPath($keys = null, $val = null, $x = null){
				return static::curryN(3, function($keys, $val, $x){
					$type = static::type($x);

					if($type === 'array'){
						$result = static::arrayClone($x);
						$current = &$result;

						foreach($keys as $key){
							$current = &$current[$key];
						}

						$current = $val;
						return $result;
					}
					elseif($type === 'object'){
						$result = clone $x;
						$current = &$result;

						foreach($keys as $key){
							$current = &$current->$key;
						}

						$current = $val;
						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#dissoc
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function dissoc($key = null, $x = null){
				return static::curryN(2, function($key, $x){
					$type = static::type($x);

					if($type === 'array'){
						/** @var array $result */
						$result = static::arrayClone($x);
						unset($result[$key]);
						return array_values($result);
					}
					elseif($type === 'object'){
						$result = clone $x;
						unset($result->$key);
						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#dissocPath
			 * @param string[] $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function dissocPath($keys = null, $x = null){
				return static::curryN(2, function($keys, $x){
					$type = static::type($x);

					if($type === 'array'){
						/** @var array $result */
						$result = static::arrayClone($x);
						$current = &$result;

						for($n = 0; $n < count($keys); $n++){
							if($n === count($keys) - 1){
								unset($current[$keys[$n]]);
							}

							if($current[$keys[$n]] !== null){
								$current = &$current[$keys[$n]];
							}
						}

						return array_values($result);
					}
					elseif($type === 'object'){
						$result = clone $x;
						$current = &$result;

						for($n = 0; $n < count($keys) - 1; $n++){
							if($current->{$keys[$n]} !== null){
								$current = &$current->{$keys[$n]};
							}

							if($n === count($keys) - 2){
								unset($current->{$keys[$n + 1]});
							}
						}

						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#clone
			 * @param mixed $x
			 * @return Closure
			 */
			public static function deepClone($x){
				return static::curryN(1, function($x){
					return unserialize(serialize($x));
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#eqProps
			 * @param string $key
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function eqProps($key = null, $x = null, $y = null){
				return static::curryN(3, function($key, $x, $y){
					$type1 = static::type($x);
					$type2 = static::type($y);

					if($type1 === 'array' && $type2 === 'array'){
						return $x[$key] === $y[$key];
					}
					elseif($type1 === 'object' && $type2 === 'object'){
						return $x->$key === $y->$key;
					}

					return false;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#evolve
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#forEachObjIndexed
			 * @param string $f
			 * @param object|array $x
			 * @return Closure
			 */
			public static function forEachObjIndexed($f = null, $x = null){
				return static::curryN(2, function($f, $x){
					foreach($x as $key => $value){
						$f($value, $key, $x);
					}

					return $x;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#has
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function has($key = null, $x = null){
				return static::curryN(2, function($key, $x){
					$type = static::type($x);

					if($type === 'object'){
						return isset($x->$key);
					}
					elseif($type === 'array'){
						return array_key_exists($key, $x);
					}

					return false;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: no object prototype in PHP
			 * https://ramdajs.com/docs/#hasIn
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#hasPath
			 * @param string[] $path
			 * @param object|array $x
			 * @return Closure
			 */
			public static function hasPath($path = null, $x = null){
				return static::curryN(2, function($path, $x){
					$type = static::type($x);
					$subObject = null;
					$counter = 0;

					if($type === 'object'){
						$subObject = clone $x;
					}
					elseif($type === 'array'){
						$subObject = static::arrayClone($x);
					}
					else{
						return false;
					}

					foreach($path as $key){
						$propType = static::type($subObject);

						if(static::has($key, $subObject) === true){
							$counter++;

							if($propType === 'array'){
								$subObject = $subObject[$key];
							}
							elseif($propType === 'object'){
								$subObject = $subObject->$key;
							}
						}
						else{
							break;
						}
					}

					return ($counter === count($path));
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#invert
			 * @param object|array $x
			 * @return Closure
			 */
			public static function invert($x = null){
				return static::curryN(1, function($x){
					/** @var array|object $props */
					$props = static::keys($x);
					$type = static::type($x);
					$len = count($props);
					$idx = 0;
					$out = null;

					if($type === 'array'){
						$out = [];

						while($idx < $len){
							$key = $props[$idx];
							$val = $x[$key];
							$list = static::has($val, $out) ? $out[$val] : ($out[$val] = []);
							$out[$val][count($list)] = $key;
							$idx += 1;
						}
					}
					elseif($type === 'object'){
						$out = new stdClass();

						while($idx < $len){
							$key = $props[$idx];
							$val = $x->$key;
							$list = static::has($val, $out) ? $out->$val : ($out->$val = []);
							$out->$val[count($list)] = $key;
							$idx += 1;
						}
					}

					return $out;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#invertObj
			 * @param object|array $x
			 * @return Closure
			 */
			public static function invertObj($x = null){
				return static::curryN(1, function($x){
					/** @var array $props */
					$props = static::keys($x);
					$type = static::type($x);
					$len = count($props);
					$idx = 0;

					if($type === 'array'){
						$out = [];

						while($idx < $len){
							$key = $props[$idx];
							$val = $x[$key];
							$out[$val] = $key;
							$idx += 1;
						}

						return $out;
					}
					elseif($type === 'object'){
						$out = new stdClass();

						while($idx < $len){
							$key = $props[$idx];
							$val = $x->$key;
							$out->$val = $key;
							$idx += 1;
						}

						return $out;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @param object|array $x
			 * @return Closure
			 */
			public static function keys($x = null){
				return static::curryN(1, function($x){
					$type = static::type($x);

					if($type === 'object'){
						return array_keys((array)$x);
					}
					elseif($type === 'array'){
						return array_keys($x);
					}

					return null;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: no object prototype in PHP
			 * https://ramdajs.com/docs/#keysIn
			 */

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#lens
			 */

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#lensIndex
			 */

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#lensPath
			 */

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#lensProp
			 */

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#lensProp
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mapObjIndexed
			 * @param callable $mapper
			 * @param object|array $x
			 * @return Closure
			 */
			public static function mapObjIndexed($mapper = null, $x = null){
				return static::curryN(2, function($mapper, $x){
					$type = static::type($x);

					if($type === 'object'){
						$result = new stdClass();

						foreach($x as $key => $value){
							$result->$key = $mapper($value, $key, $x);
						}

						return $result;
					}
					elseif($type === 'array'){
						$result = [];

						foreach($x as $key => $value){
							$result[$key] = $mapper($value, $key, $x);
						}

						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#merge
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function merge($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$type1 = static::type($x);
					$type2 = static::type($y);

					if($type1 === 'object' && $type2 === 'object'){
						return (object)array_merge((array)$x, (array)$y);
					}
					elseif($type1 === 'array' && $type2 === 'array'){
						return array_merge($x, $y);
					}

					return null;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#mergeDeepLeft
			 */

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#mergeDeepRight
			 */

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#mergeDeepWith
			 */

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#mergeDeepWithKey
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeLeft
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeLeft($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$type1 = static::type($x);
					$type2 = static::type($y);

					if($type1 === 'object' && $type2 === 'object'){
						return (object)array_merge((array)$y, (array)$x);
					}
					elseif($type1 === 'array' && $type2 === 'array'){
						return array_merge($y, $x);
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeRight
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeRight($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return static::merge($x, $y);
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeWith
			 * @param callable $f
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeWith($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					return static::mergeWithKey(function($_, $_l, $_r) use ($f){
						return $f($_, $_l, $_r);
					}, $x, $y);
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeWithKey
			 * @param callable $f
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeWithKey($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					$type1 = static::type($x);
					$type2 = static::type($y);

					if($type1 === 'object' && $type2 === 'object'){
						$result = new stdClass();

						foreach($x as $key => $val){
							if(static::has($key, $x)){
								$result->$key = static::has($key, $y) ? $f($key, $x->$key, $y->$key) : $x->$key;
							}
						}

						foreach($y as $key => $val){
							if(static::has($key, $y) && !(static::has($key, $result))){
								$result->$key = $y->$key;
							}
						}

						return $result;
					}
					elseif($type1 === 'array' && $type2 === 'array'){
						$result = [];

						foreach($x as $key => $val){
							if(static::has($key, $x)){
								$result[$key] = static::has($key, $y) ? $f($key, $x[$key], $y[$key]) : $x[$key];
							}
						}

						foreach($y as $key => $val){
							if(static::has($key, $y) && !(static::has($key, $result))){
								$result[$key] = $y[$key];
							}
						}

						return $result;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#objOf
			 * @param string $key
			 * @param mixed $val
			 * @return Closure
			 */
			public static function objOf($key = null, $val = null){
				return static::curryN(2, function($key, $val){
					$obj = new stdClass();
					$obj->$key = $val;
					return $obj;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#omit
			 * @param mixed[] $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function omit($keys = null, $x = null){
				return static::curryN(2, function($keys, $x){
					$type = static::type($x);

					if($type === 'object'){
						$newObj = clone $x;

						foreach($keys as $key){
							unset($newObj->$key);
						}

						return $newObj;
					}
					elseif($type === 'array'){
						/** @var array $newArr */
						$newArr = static::arrayClone($x);

						foreach($keys as $key){
							unset($newArr[$key]);
						}

						return $newArr;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.omitBy
			 * @param callable $f
			 * @param object|array $x
			 * @return Closure
			 */
			public static function omitBy($f = null, $x = null){
				return static::curryN(2, function($f, $x){
					$type = static::type($x);

					if($type === 'object'){
						$newObj = clone $x;
						$keys = static::keys($newObj);

						foreach($keys as $key){
							if($f($newObj->$key, $key) === true){
								unset($newObj->$key);
							}
						}

						return $newObj;
					}
					elseif($type === 'array'){
						/** @var array $newArr */
						$newArr = static::arrayClone($x);
						$keys = static::keys($newArr);

						foreach($keys as $key){
							if($f($newArr[$key], $key) === true){
								unset($newArr[$key]);
							}
						}

						return $newArr;
					}

					return null;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#over
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#path
			 * @param string[] $path
			 * @param object|array $x
			 * @return Closure
			 */
			public static function path($path = null, $x = null){
				return static::curryN(2, function($path, $x){
					$current = &$x;

					foreach($path as $key){
						$current = (static::type($x) === 'array') ? $current[$key] : $current->$key;
					}

					return $current;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#paths
			 * @param string[] $paths
			 * @param object|array $x
			 * @return Closure
			 */
			public static function paths($paths = null, $x = null){
				return static::curryN(2, function($paths, $x){
					$results = [];

					foreach($paths as $path){
						array_push($results, static::path($path, $x));
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pathOr
			 * @param mixed $val
			 * @param string[] $path
			 * @param object|array $x
			 * @return Closure
			 */
			public static function pathOr($val = null, $path = null, $x = null){
				return static::curryN(3, function($val, $path, $x){
					return static::defaultTo($val, static::path($path, $x));
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pick
			 * @param string[] $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function pick($keys = null, $x = null){
				return static::curryN(2, function($keys, $x){
					$type = static::type($x);

					if($type === 'object'){
						$newObj = new stdClass();

						foreach($keys as $key){
							if(isset($x->$key)){
								$newObj->$key = $x->$key;
							}
						}

						return $newObj;
					}
					elseif($type === 'array'){
						$newArr =[];

						foreach($keys as $key){
							if(array_key_exists($key, $x)){
								$newArr[$key] = $x[$key];
							}
						}

						return $newArr;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pickAll
			 * @param string[] $keys
			 * @param mixed $defaultVal
			 * @param object|array $x
			 * @return Closure
			 */
			public static function pickAll($keys = null, $defaultVal = null, $x = null){
				return static::curryN(3, function($keys, $defaultVal, $x){
					$type = static::type($x);

					if($type === 'object'){
						$newObj = new stdClass();

						foreach($keys as $key){
							$newObj->$key = isset($x->$key) ? $x->$key : $defaultVal;
						}

						return $newObj;
					}
					elseif($type === 'array'){
						$newArr = [];

						foreach($keys as $key){
							$newArr[$key] = array_key_exists($key, $x) ? $x[$key] : $defaultVal;
						}

						return $newArr;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pickBy
			 * @param callable $pred
			 * @param object $x
			 * @return Closure
			 */
			public static function pickBy($pred = null, $x = null){
				return static::curryN(2, function($pred, $x){
					$type = static::type($x);

					if($type === 'object'){
						$newObj = new stdClass();
						$keys = static::keys($x);

						foreach($keys as $key){
							if($pred($x->$key, $key)){
								$newObj->$key = $x->$key;
							}
						}

						return $newObj;
					}
					elseif($type === 'array'){
						$newArr = [];
						$keys = static::keys($x);

						foreach($keys as $key){
							if($pred($x[$key], $key)){
								$newArr[$key] = $x[$key];
							}
						}

						return $newArr;
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#project
			 * @param string[] $props
			 * @param object[]|array[] $x
			 * @return Closure
			 */
			public static function project($props = null, $x = null){
				return static::curryN(2, function($props, $x){
					$type = static::type($x);

					if($type !== 'array'){
						return null;
					}

					if(empty($x)){
						return [];
					}

					$type0 = static::type($x[0]);
					$results = [];

					if($type0 === 'object'){
						foreach($x as $arr){
							$newObj = new stdClass();

							foreach($props as $prop){
								$newObj->$prop = static::has($prop, $arr) ? $arr->$prop : null;
							}

							array_push($results, $newObj);
						}
					}
					elseif($type0 === 'array'){
						foreach($x as $arr){
							$newArr = [];

							foreach($props as $prop){
								$newArr[$prop] = static::has($prop, $arr) ? $arr[$prop] : null;
							}

							array_push($results, $newArr);
						}
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#prop
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function prop($key = null, $x = null){
				return static::curryN(2, function($key, $x){
					$type = static::type($x);

					if($type === 'object'){
						return static::has($key, $x) ? $x->$key : null;
					}
					elseif($type === 'array'){
						return $x[$key];
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#propOr
			 * @param string $val
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function propOr($val = null, $key = null, $x = null){
				return static::curryN(3, function($val, $key, $x){
					$c = static::cond(
						[static::has($key), static::prop($key)],
						[static::always(true), static::always($val)]
					);
					return $c($x);
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#props
			 * @param string[] $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function props($keys = null, $x = null){
				return static::curryN(2, function($keys, $x){
					return static::map(function($key) use ($x){
						return static::prop($key, $x);
					}, $keys);
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#set
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#toPairs
			 * @param object|array $x
			 * @return Closure
			 */
			public static function toPairs($x = null){
				return static::curryN(1, function($x){
					$type = static::type($x);
					$keys = static::keys($x);
					$results = [];

					if($type === 'object'){
						foreach($keys as $key){
							array_push($results, [$key, $x->$key]);
						}
					}
					elseif($type === 'array'){
						foreach($keys as $key){
							array_push($results, [$key, $x[$key]]);
						}
					}

					return null;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramda-extension.firebaseapp.com/docs/#toEntries
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function toEntries($obj = null){
				return static::curryN(1, function($obj){
					$results = [];

					foreach($obj as $key => $value){
						$newObj = new stdClass();
						$newObj->$key = $value;
						array_push($results, $newObj);
					}

					return $results;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: no object prototype in PHP
			 * https://ramdajs.com/docs/#toPairsIn
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#values
			 * @param object|array $x
			 * @return Closure
			 */
			public static function values($x = null){
				return static::curryN(1, function($x){
					$type = static::type($x);
					$keys = static::keys($x);
					$results = [];

					if($type === 'object'){
						foreach($keys as $key){
							array_push($results, $x->$key);
						}
					}
					elseif($type === 'array'){
						foreach($keys as $key){
							array_push($results, $x[$key]);
						}
					}

					return null;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: no object prototype in PHP
			 * https://ramdajs.com/docs/#valuesIn
			 */

			/*
			 * OMITTED
			 * reason: Lens functions beyond the scope of this library
			 * https://ramdajs.com/docs/#view
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#where
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function where($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$type = static::type($x);
					$keys = static::keys($x);

					if($type === 'object'){
						foreach($keys as $prop){
							if(static::has($prop, $x)){
								$f = $x->$prop;

								if(!$f($y->$prop)){
									return false;
								}
							}
						}
					}
					elseif($type === 'array'){
						foreach($keys as $prop){
							if(static::has($prop, $x)){
								$f = $x[$prop];

								if(!$f($y[$prop])){
									return false;
								}
							}
						}
					}

					return true;
				})(...func_get_args());
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#whereEq
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function whereEq($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$type = static::type($x);
					$keys = static::keys($x);

					if($type === 'object'){
						foreach($keys as $prop){
							if(static::has($prop, $x) && $x->$prop !== $y->$prop){
								return false;
							}
						}
					}
					elseif($type === 'array'){
						foreach($keys as $prop){
							if(static::has($prop, $x) && $x[$prop] !== $y[$prop]){
								return false;
							}
						}
					}

					return true;
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="RELATION">

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#clamp
			 * @param int|float $min
			 * @param int|float $max
			 * @param int|float $x
			 * @return Closure
			 */
			public static function clamp($min = null, $max = null, $x = null){
				return static::curryN(3, function($min, $max, $x){
					return max($min, min($max, $x));
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#countBy
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function countBy($f = null, $arr = null){
				return static::curryN(2, function($f, $arr){
					return static::reduceBy(function($acc){
						return $acc + 1;
					}, 0, $f, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#difference
			 * @param array $x
			 * @param array $y
			 * @return Closure
			 */
			public static function difference($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$out = [];
					$idx = 0;
					$xLen = count($x);
					$yLen = count($y);
					$toFilterOut = [];

					for($i = 0; $i < $yLen; $i += 1){
						if(!in_array($y[$i], $toFilterOut)){
							$toFilterOut[] = $y[$i];
						}
					}

					while($idx < $xLen){
						if(!in_array($x[$idx], $toFilterOut)){
							$out[count($out)] = $x[$idx];
						}

						$idx += 1;
					}

					return $out;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#differenceWith
			 * @param callable $f
			 * @param array $x
			 * @param array $y
			 * @return Closure
			 */
			public static function differenceWith($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					$results = [];
					$idx = 0;
					$firstLen = count($x);

					while($idx < $firstLen){
						if(!static::_includesWith($f, $x[$idx], $y) &&
							!static::_includesWith($f, $x[$idx], $results)){
							array_push($results, $x[$idx]);
						}

						$idx += 1;
					}

					return $results;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#eqBy
			 * @param callable $f
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function eqBy($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					return static::equals($f($x), $f($y));
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#equals
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function equals($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $x === $y;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToEmptyArray
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToEmptyArray($x = null){
				return static::curryN(1, function($x){
					return $x === [];
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToEmptyObject
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToEmptyObject($x = null){
				return static::curryN(1, function($x){
					/** @var array $keys */
					$keys = static::keys($x);
					return count($keys) === 0;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToEmptyString
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToEmptyString($x = null){
				return static::curryN(1, function($x){
					return $x === '';
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToFalse
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToFalse($x = null){
				return static::curryN(1, function($x){
					return $x === false;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToNull
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToNull($x = null){
				return static::curryN(1, function($x){
					return $x === null;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToOne
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToOne($x = null){
				return static::curryN(1, function($x){
					return $x === 1;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToTrue
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToTrue($x = null){
				return static::curryN(1, function($x){
					return $x === true;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToZero
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToZero($x = null){
				return static::curryN(1, function($x){
					return $x === 0;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#gt
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function gt($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y > $x;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#gte
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function gte($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y >= $x;
				})(...func_get_args());
			}

			/*
			 * OMITTED
			 * reason: not sure how to implement
			 * https://ramdajs.com/docs/#identical
			 */

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#innerJoin
			 * @param callable $pred
			 * @param array $x
			 * @param array $y
			 * @return Closure
			 */
			public static function innerJoin($pred = null, $x = null, $y = null){
				return static::curryN(3, function($pred, $x, $y){
					return static::filter(function($x) use ($pred, $y){
						return static::_includesWith($pred, $x, $y);
					}, $x);
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#intersection
			 * @param array $x
			 * @param array $y
			 * @return Closure
			 */
			public static function intersection($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					$longArr = null;
					$shortArr = null;

					if(count($x) > count($y)){
						$longArr = $x;
						$shortArr = $y;
					}
					else{
						$longArr = $y;
						$shortArr = $x;
					}

					$results = array_filter($longArr, function($x) use ($shortArr){
						foreach($shortArr as $item){
							if($item === $x){
								return true;
							}
						}

						return false;
					});

					return static::uniq($results);
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * Is number even.
			 * @param float|int $x
			 * @return Closure
			 */
			public static function isEven($x){
				return static::curryN(1, function($x){
					return $x % 2 === 0;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * Is number odd.
			 * @param float|int $x
			 * @return Closure
			 */
			public static function isOdd($x){
				return static::curryN(1, function($x){
					return $x % 2 !== 0;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#lt
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function lt($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y < $x;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#lte
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function lte($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y <= $x;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#max
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function max($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y > $x ? $y : $x;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#maxBy
			 * @param callable $f
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function maxBy($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					return $f($y) > $f($x) ? $y : $x;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#min
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function min($x = null, $y = null){
				return static::curryN(2, function($x, $y){
					return $y < $x ? $y : $x;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#minBy
			 * @param callable $f
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function minBy($f = null, $x = null, $y = null){
				return static::curryN(3, function($f, $x, $y){
					return $f($y) < $f($x) ? $y : $x;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#pathEq
			 * @param string[] $path
			 * @param mixed $val
			 * @param object $obj
			 * @return Closure
			 */
			public static function pathEq($path = null, $val = null, $obj = null){
				return static::curryN(3, function($path, $val, $obj){
					$current = &$obj;

					foreach($path as $key){
						$current = &$current->$key;
					}

					return $current === $val;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.pathNotEq
			 * @param string[] $path
			 * @param mixed $val
			 * @param object $obj
			 * @return Closure
			 */
			public static function pathNotEq($path = null, $val = null, $obj = null){
				return static::curryN(3, function($path, $val, $obj){
					$current = &$obj;

					foreach($path as $key){
						$current = &$current->$key;
					}

					return $current !== $val;
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#propEq
			 * @param string $key
			 * @param mixed $val
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function propEq($key = null, $val = null, $obj = null){
				return static::curryN(3, function($key, $val, $obj){
					$p = static::pipe(static::prop($key), static::equals($val));
					return $p($obj);
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.propNotEq
			 * @param string $key
			 * @param mixed $val
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function propNotEq($key = null, $val = null, $obj = null){
				return static::curryN(3, function($key, $val, $obj){
					$p = static::pipe(static::prop($key), static::notEqual($val));
					return $p($obj);
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#sortBy
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortBy($f = null, $arr = null){
				return static::curryN(2, function($f, $arr){
					return R::sort(function($a, $b) use ($f){
						$aa = $f($a);
						$bb = $f($b);

						if($a == $b){
							return 0;
						}

						return ($aa < $bb) ? -1 : 1;
					}, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#sortWith
			 * @param callable[] $fArr
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortWith($fArr = null, $arr = null){
				return static::curryN(2, function($fArr, $arr){
					return R::sort(function($a, $b) use ($fArr){
						$result = 0;
						$i = 0;

						while($result === 0 && $i < count($fArr)){
							$result = $fArr[$i]($a, $b);
							$i += 1;
						}

						return $result;
					}, $arr);
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#symmetricDifference
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function symmetricDifference($arr1 = null, $arr2 = null){
				return static::curryN(2, function($arr1, $arr2){
					return static::concat(static::difference($arr1, $arr2), static::difference($arr2, $arr1));
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#symmetricDifferenceWith
			 * @param callable $pred
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function symmetricDifferenceWith($pred = null, $arr1 = null, $arr2 = null){
				return static::curryN(3, function($pred, $arr1, $arr2){
					return static::concat(static::differenceWith($pred, $arr1, $arr2), static::differenceWith($pred, $arr2, $arr1));
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#union
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function union($arr1 = null, $arr2 = null){
				return static::curryN(2, function($arr1, $arr2){
					return static::uniq(array_merge($arr1, $arr2));
				})(...func_get_args());
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#unionWith
			 * @param callable $pred
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function unionWith($pred = null, $arr1 = null, $arr2 = null){
				return static::curryN(3, function($pred, $arr1, $arr2){
					return static::uniqWith($pred, array_merge($arr1, $arr2));
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="STRING">

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#capitalizeAll
			 * @param string $str
			 * @return Closure
			 */
			public static function capitalizeAll($str = null){
				return static::curryN(1, function($str){
					return ucwords($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#capitalizeFirst
			 * @param string $str
			 * @return Closure
			 */
			public static function capitalizeFirst($str = null){
				return static::curryN(1, function($str){
					return ucfirst($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#endsWithSuffix
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function endsWithSuffix($str = null, $in = null){
				return static::curryN(2, function($str, $in){
					return (static::takeLast(strlen($str), $in) === $str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#endsWithSuffixIgnoreCase
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function endsWithSuffixIgnoreCase($str = null, $in = null){
				return static::curryN(2, function($str, $in){
					$suffix = static::takeLast(strlen($str), $in);
					return strtolower($suffix) === strtolower($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsStringIgnoreCase
			 * @param mixed $str1
			 * @param string $str2
			 * @return Closure
			 */
			public static function equalsStringIgnoreCase($str1 = null, $str2 = null){
				return static::curryN(2, function($str1, $str2){
					return strtolower($str1) === strtolower($str2);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#match
			 * @param string $regEx
			 * @param string $str
			 * @return Closure
			 */
			public static function match($regEx = null, $str = null){
				return static::curryN(2, function($regEx, $str){
					$results = [];
					preg_match_all($regEx, $str, $results);
					return $results[0];
				})(...func_get_args());
			}

			/**
			 * Note: does not support regex.
			 * @internal String
			 * @link https://ramdajs.com/docs/#replace
			 * @param string $find
			 * @param string $replaceWith
			 * @param string $str
			 * @return Closure
			 */
			public static function replace($find = null, $replaceWith = null, $str = null){
				return static::curryN(3, function($find, $replaceWith, $str){
					$pos = strpos($str, $find);
					return ($pos !== false) ? substr_replace($str, $replaceWith, $pos, strlen($find)) : $str;
				})(...func_get_args());
			}

			/**
			 * Note: does not support regex.
			 * @internal String
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.replaceAll
			 * @param string $find
			 * @param string $replaceWith
			 * @param string $str
			 * @return Closure
			 */
			public static function replaceAll($find = null, $replaceWith = null, $str = null){
				return static::curryN(3, function($find, $replaceWith, $str){
					return str_replace($find, $replaceWith, $str);
				})(...func_get_args());
			}

			/**
			 * Same as "replace()" but with regex support.
			 * @internal String
			 * @param string $regEx
			 * @param string $replaceWith
			 * @param string $str
			 * @return Closure
			 */
			public static function replaceRegEx($regEx = null, $replaceWith = null, $str = null){
				return static::curryN(3, function($regEx, $replaceWith, $str){
					return preg_replace($regEx, $replaceWith, $str);
				})(...func_get_args());
			}

			/**
			 * Replaces an index numbered token string for each item in $replaceWith.
			 * @internal String
			 * @example R::replaceTokenStrings(["John", "Doe"], "last_name: {1}, first_name: {0}")) => "last_name: Doe, first_name: John"
			 * @param string[] $replaceWith
			 * @param string $str
			 * @return Closure
			 */
			public static function replaceTokenStrings($replaceWith = null, $str = null){
				return static::curryN(2, function($replaceWith, $str){
					$strCopy = $str;

					for($n = 0; $n < count($replaceWith); $n++){
						$strCopy = implode($replaceWith[$n], explode('{'.$n.'}', $strCopy));
					}

					return $strCopy;
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#split
			 * @param string $on
			 * @param string $str
			 * @return Closure
			 */
			public static function split($on = null, $str = null){
				return static::curryN(2, function($on, $str){
					return empty($on) ? str_split($str) : explode($on, $str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#startsWithPrefix
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function startsWithPrefix($str = null, $in = null){
				return static::curryN(2, function($str, $in){
					return (static::take(strlen($str) - 1, $in) === $str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#startsWithPrefixIgnoreCase
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function startsWithPrefixIgnoreCase($str = null, $in = null){
				return static::curryN(2, function($str, $in){
					$prefix = static::take(strlen($str) - 1, $in);
					return strtolower($prefix) === strtolower($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#test
			 * @param string $regEx
			 * @param string $str
			 * @return Closure
			 */
			public static function test($regEx = null, $str = null){
				return static::curryN(2, function($regEx, $str){
					return preg_match($regEx, $str) ? true : false;
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toCamelCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toCamelCase($str = null){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = ucwords(trim($str));
					$str = str_replace(" ", "", $str);
					return lcfirst($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toScreamingSnakeCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toConstCase($str = null){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = strtoupper(trim($str));
					return str_replace(" ", "_", $str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toKebabCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toKebabCase($str = null){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = trim($str);
					return str_replace(" ", "-", $str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#toLower
			 * @param string $str
			 * @return Closure
			 */
			public static function toLower($str = null){
				return static::curryN(1, function($str){
					return strtolower($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toPascalCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toPascalCase($str = null){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = ucwords(trim($str));
					return str_replace(" ", "", $str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toSnakeCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toSnakeCase($str = null){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = trim($str);
					return str_replace(" ", "_", $str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#toString
			 * @param mixed $x
			 * @return Closure
			 */
			public static function toString($x = null){
				return static::curryN(1, function($x){
					$type = static::type($x);

					if($type === 'object' || $type === 'array'){
						return json_encode($x);
					}

					return (string)$x;
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#toUpper
			 * @param string $str
			 * @return Closure
			 */
			public static function toUpper($str = null){
				return static::curryN(1, function($str){
					return strtoupper($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toUpperFirst
			 * @param string $str
			 * @return Closure
			 */
			public static function toUpperFirst($str = null){
				return static::curryN(1, function($str){
					return ucfirst($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#trim
			 * @param string $str
			 * @return Closure
			 */
			public static function trim($str = null){
				return static::curryN(1, function($str){
					return trim($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.trimEnd
			 * @param string $str
			 * @return Closure
			 */
			public static function trimEnd($str = null){
				return static::curryN(1, function($str){
					return rtrim($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.trimStart
			 * @param string $str
			 * @return Closure
			 */
			public static function trimStart($str = null){
				return static::curryN(1, function($str){
					return ltrim($str);
				})(...func_get_args());
			}

			/**
			 * @internal String
			 * @param string[] $arr - can contain 1 or 2 items only
			 * @param string $str
			 * @return Closure
			 */
			public static function wrapWith($arr = null, $str = null){
				return static::curryN(2, function($arr, $str){
					return (count($arr) === 2) ? "{$arr[0]}{$str}{$arr[1]}" : "{$arr[0]}{$str}{$arr[0]}";
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="TYPE">

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#between
			 * @param int|float $min
			 * @param int|float $max
			 * @param int|float $val
			 * @return Closure
			 */
			public static function between($min = null, $max = null, $val = null){
				return static::curryN(3, function($min, $max, $val){
					if($val >= $min && $val <= $max){
						return true;
					}

					return false;
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isBoolean
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isBoolean($val = null){
				return static::curryN(1, function($val){
					return static::isType($val) === 'boolean';
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isArray
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isArray($val = null){
				return static::curryN(1, function($val){
					return static::isType($val) === 'array';
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isFloat($val = null){
				return static::curryN(1, function($val){
					return is_float($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isFunction
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isFunction($val = null){
				return static::curryN(1, function($val){
					return is_callable($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isInt($val = null){
				return static::curryN(1, function($val){
					return is_int($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isNegative
			 * @param int|float $x
			 * @return Closure
			 */
			public static function isNegative($x = null){
				return static::curryN(1, function($x){
					return $x < 0;
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#isNil
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isNil($val = null){
				return static::curryN(1, function($val){
					return $val === null;
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isNilOrEmpty
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isNilOrEmpty($val = null){
				return static::curryN(1, function($val){
					return ($val === null) || empty($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isNumber
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isNumber($x = null){
				return static::curryN(1, function($x){
					return is_numeric($x);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isObject
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isObject($val = null){
				return static::curryN(1, function($val){
					return is_object($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isPair
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isPair($x = null){
				return static::curryN(1, function($x){
					return is_array($x) && (count($x) === 2);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isPositive
			 * @param int|float $x
			 * @return Closure
			 */
			public static function isPositive($x = null){
				return static::curryN(1, function($x){
					return $x >= 0;
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isSparseArray
			 * @param mixed[] $arr
			 * @return Closure
			 */
			public static function isSparseArray($arr = null){
				return static::curryN(1, function($arr){
					return static::last(static::keys($arr)) > count($arr) - 1;
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isString
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isString($val = null){
				return static::curryN(1, function($val){
					return is_string($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#is
			 * @param string $type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isType($type = null, $val = null){
				return static::curryN(2, function($type, $val){
					$p = static::pipe(static::type(), static::equals($type));
					return $p($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isVarSet($val = null){
				return static::curryN(1, function($val){
					return isset($val);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#propIs
			 * @param string $type
			 * @param string $key
			 * @param object $obj
			 * @return Closure
			 */
			public static function propIs($type = null, $key = null, $obj = null){
				return static::curryN(3, function($type, $key, $obj){
					$p = static::pipe(static::prop($key), static::isType($type));
					return $p($obj);
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @param string $x
			 * @return Closure
			 */
			public static function toNumber($x = null){
				return static::curryN(1, function($x){
					if(!is_numeric($x)){
						return null;
					}
					elseif(static::includes('.', $x)){
						return floatval($x);
					}
					else{
						return intval($x);
					}
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * Copies and casts a var to the specified type.
			 * @param string $type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function toType($type = null, $val = null){
				return static::curryN(2, function($type, $val){
					$copy = $val;
					$valType = static::type($val);

					if($valType === 'object'){
						$copy = clone $val;
					}
					elseif($valType === 'array'){
						$copy = static::arrayClone($val);
					}

					$success = settype($copy, $type);
					return $success === true ? $copy : null;
				})(...func_get_args());
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#type
			 * @param mixed $x
			 * @return Closure
			 */
			public static function type($x = null){
				return static::curryN(1, function($x){
					return gettype($x);
				})(...func_get_args());
			}

			//</editor-fold>

			//<editor-fold desc="__INTERNAL__">

			private static function _includesWith($pred, $x, $list){
				$i = 0;
				$l = count($list);

				while($i < $l){
					if($pred($x, $list[$i])){
						return true;
					}

					$i += 1;
				}

				return false;
			}

			//</editor-fold>
		}

		class Placeholder{
			//used for curry arg skipping
		}
	}

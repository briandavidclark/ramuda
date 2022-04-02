<?php

	/**
	 * Based on Ramda v0.28 - https://ramdajs.com/docs/
	 * Based on Ramda Adjunct v2.24.0 - https://char0n.github.io/ramda-adjunct/2.24.0/RA.html
	 * Based on Ramda Extension v0.10.3 - https://ramda-extension.firebaseapp.com/docs/
	 *
	 * Requires a minimum of PHP 5.6
	 *
	 * @link https://github.com/briandavidclark/ramuda
	 * @author Brian Clark
	 * @since 2020-05-13
	 */

	namespace ramuda {

		use Closure;
		use Exception;
		use RecursiveArrayIterator;
		use RecursiveIteratorIterator;
		use ReflectionException;
		use ReflectionFunction;
		use stdClass;
		use Traversable;

		abstract class R{

			public static $_;

			//<editor-fold desc="FUNCTION">

			/*
			 * OMITTED
			 * reason: not sure if necessary because R::map provides index.
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
			 * @param array $vArr
			 * @return Closure|array
			 */
			public static function ap(...$args){
				return static::curryN(2, function($fArr, $vArr){
					$results = [];

					foreach($fArr as $f){
						foreach($vArr as $v){
							$results[] = $f($v);
						}
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#apply
			 * @param callable $f
			 * @param array $argArr
			 * @return Closure|mixed
			 */
			public static function apply(...$args){
				return static::curryN(2, function($f, $argArr){
					return call_user_func_array($f, $argArr);
				})(...$args);
			}

			/**
			 * NOTE: Unlike the Ramda `applySpec` function, the max arity of the applied functions must be provided as the `$arity` arg.
			 * @internal Function
			 * @link https://ramdajs.com/docs/#applySpec
			 * @param int $arity
			 * @param array|object $spec
			 * @return Closure|mixed
			 */
			public static function applySpec(...$args){
				return static::curryN(2, function($arity, $spec){
					return static::curryN($arity, function(...$params) use ($arity, $spec){
						return array_map(function($val) use ($arity, $params){
							if((gettype($val) === 'array' || gettype($val) === 'object') && !is_callable($val)){
								return static::applySpec($arity, $val)(...$params);
							}
							elseif(is_callable($val)){
								return $val(...$params);
							}

							return $val;
						}, $spec);
					});
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#applyTo
			 * @param mixed $x
			 * @param callable $f
			 * @return Closure|mixed
			 */
			public static function applyTo(...$args){
				return static::curryN(2, function($x, $f){
					return $f($x);
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#ascend
			 * @param callable $f
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure|int
			 */
			public static function ascend(...$args){
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
				})(...$args);
			}

			/*
			 * OMITTED
			 * reason: Not sure if possible in PHP. Arity mismatch throws ArgumentCountError.
			 * https://ramdajs.com/docs/#binary
			 */

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP
			 * https://ramdajs.com/docs/#bind
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#call
			 * @param callable $f
			 * @return mixed
			 */
			public static function call($f, ...$args){
				return call_user_func_array($f, $args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#comparator
			 * @param callable $pred
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure|int
			 */
			public static function comparator(...$args){
				return static::curryN(3, function($pred, $x, $y){
					if($pred($x, $y)){
						return -1;
					}
					elseif($pred($y, $x)){
						return 1;
					}

					return 0;
				})(...$args);
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
			public static function composeWith(...$args){
				return static::curryN(2, function($f, $fArr){
					return function($val) use ($f, $fArr){
						/** @var array $newFuncArr */
						$newFuncArr = static::reverse($fArr);

						return array_reduce($newFuncArr, function($acc, $fItem) use ($f){
							return $f($fItem, $acc);
						}, $val);
					};
				})(...$args);
			}

			/**
			 * NOTE: If class is namespaced, the fully qualified name must be sent to $className.
			 * @example R::construct('\MyNamespace\MyClass');
			 * @internal Function
			 * @link https://ramdajs.com/docs/#construct
			 * @param string $className
			 * @return Closure
			 */
			public static function construct($className){
				return function(...$args) use ($className){
					return new $className(...$args);
				};
			}

			/**
			 * NOTE: If class is namespaced, the fully qualified name must be sent to $className.
			 * @example R::constructN(3, '\MyNamespace\MyClass');
			 * @internal Function
			 * @link https://ramdajs.com/docs/#constructN
			 * @param int $arity
			 * @param string $className
			 * @return Closure
			 */
			public static function constructN(...$args){
				return static::curryN(2, function($arity, $className){
					return static::curryN($arity, function(...$constructorArgs) use ($className){
						return new $className(...$constructorArgs);
					});
				})(...$args);
			}

			/**
			 * NOTE: Unlike the Ramda `converge` function, the max arity of the `$fs` functions must be provided as the `$arity` arg.
			 * @internal Function
			 * @link https://ramdajs.com/docs/#converge
			 * @param int $arity
			 * @param callable $f
			 * @param callable[] $fs
			 * @return Closure
			 */
			public static function converge(...$args){
				return static::curryN(3, function($arity, $f, $fs){
					return static::curryN($arity, function(...$params) use ($f, $fs){
						return $f(...array_map(function($fn) use ($params){
							return $fn(...$params);
						}, $fs));
					});
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#curry
			 * @param callable|null $f
			 * @return Closure
			 * @throws ReflectionException
			 */
			public static function curry($f){
				return static::curryN(static::_getArgCount($f), $f);
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
						$combined = [];
						$combinedIdx = 0;
						$args = func_get_args();

						while($combinedIdx < count($recv) || $argsIdx < count($args)){
							if($combinedIdx < count($recv)
								&& ($recv[$combinedIdx] !== static::$_ || $argsIdx > count($args))){
								$result = $recv[$combinedIdx];
							}
							else{
								$result = $args[$argsIdx];
								$argsIdx += 1;
							}

							$combined[$combinedIdx] = $result;
							$combinedIdx += 1;

							if($result !== static::$_){
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
			 * @return Closure|int
			 */
			public static function descend(...$args){
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
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#empty
			 * @param mixed $x
			 * @return Closure|mixed
			 */
			public static function emptyVal(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);

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
				})(...$args);
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

			/**
			 * NOTE: Unlike the Ramda `flip` function, the arity of the `$f` function must be provided as the `$arity` arg.
			 * @internal Function
			 * @link https://ramdajs.com/docs/#flip
			 * @param int $arity
			 * @param callable $f
			 * @return Closure
			 */
			public static function flip(...$args){
				return static::curryN(2, function($arity, $f){
					return static::curryN($arity, function(...$params) use ($f){
						/** @var array $newArgs */
						$newArgs = static::moveLeft(1, 1, $params);
						return call_user_func_array($f, $newArgs);
					});
				})(...$args);
			}

			/**
			 * NOTE: Returned function will also accept an array.
			 * @internal Function
			 * @link https://ramdajs.com/docs/#invoker
			 * @param int $arity
			 * @param callable $method
			 * @return Closure
			 */
			public static function invoker(...$args){
				return static::curryN(2, function($arity, $method){
					return static::curryN($arity, function(...$params) use ($method){
						$obj = $params[count($params) - 1];

						if(gettype($obj) === 'array'){
							return call_user_func_array($obj[$method], $params);
						}

						return call_user_func_array(array($obj, $method), $params);
					});
				})(...$args);
			}

			/**
			 * @internal Function
			 * Hooks into a pipeline.
			 * @param callable $f
			 * @param mixed $val
			 * @return Closure
			 */
			public static function hook(...$args){
				return static::curryN(2, function($f, $val){
					return $f($val);
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#identity
			 * @param mixed $x
			 * @return Closure|mixed
			 */
			public static function identity(...$args){
				return static::curryN(1, function($x){
					return $x;
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#juxt
			 * @param callable[] $fArr
			 * @return Closure|mixed
			 */
			public static function juxt($fArr = null){
				return function(...$args) use ($fArr){
					return array_map(function($f) use ($args){
						return $f(...$args);
					}, $fArr);
				};
			}

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP
			 * https://ramdajs.com/docs/#lift
			 */

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP
			 * https://ramdajs.com/docs/#liftN
			 */

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#memoizeWith
			 * @param callable $keyF - must return an int or string value
			 * @param callable $f
			 * @param mixed $val
			 * @return Closure|mixed
			 */
			public static function memoizeWith(...$args){
				$cache = [];

				return static::curryN(3, function($keyF, $f, $val) use (&$cache){
					$key = $keyF($val);

					if(!array_key_exists($key, $cache)){
						$cache[$key] = $f($val);
					}

					return $cache[$key];
				})(...$args);
			}

			/**
			 * Returns a curried "reference" to a class method. Similar to "invoker" but
			 * returned function does not require the instance to be passed as an argument.
			 * Useful because PHP class methods can't be called by reference.
			 *
			 * @internal Function
			 * @param int $arity - the class method arity
			 * @param object $instance - a class instance
			 * @param string $method - the method name
			 * @return Closure
			 */
			public static function methodRef($arity, $instance, $method){
				return static::curryN($arity, function(...$xs) use ($instance, $method, $arity){
					return call_user_func_array(array($instance, $method), $xs);
				});
			}

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP. Arity mismatch throws ArgumentCountError.
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
			 * @return Closure|mixed
			 */
			public static function o(...$args){
				return static::curryN(3, function($f1, $f2, $x){
					return $f1($f2($x));
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#of
			 * @param int $x
			 * @return Closure|array
			 */
			public static function of(...$args){
				return static::curryN(1, function($x){
					return [$x];
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#on
			 * @param callable $f1
			 * @param callable $f2
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure|array
			 */
			public static function on(...$args){
				return static::curryN(4, function($f1, $f2, $x, $y){
					return $f1($f2($x), $f2($y));
				})(...$args);
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
			 * @param array $argsArr
			 * @return Closure
			 */
			public static function partial($f, $argsArr){
				return function(...$args) use ($f, $argsArr){
					return $f(...array_merge($argsArr, $args));
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#partialObject
			 * @param callable $f
			 * @param object $obj
			 * @return Closure|mixed
			 */
			public static function partialObject(...$args){
				return static::curryN(2, function($f, $obj){
					return function($obj2) use ($f, $obj){
						return $f(R::mergeDeepRight($obj, $obj2));
					};
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#partialRight
			 * @param callable $f
			 * @param array $argsArr
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
			 * @param callable ...$fs
			 * @return Closure
			 * @throws Exception
			 */
			public static function pipe(...$fs){
				return function(...$vals) use ($fs){
					if(count($fs) < 1){
						throw new Exception('Function "pipe" must have at least one argument.');
					}
					else{
						$init = call_user_func_array($fs[0], $vals);

						if(count($fs) > 1){
							return array_reduce(
								array_slice($fs, 1),
								function($acc, $f){
									return $f($acc);
								},
								$init
							);
						}
					}

					return $init;
				};
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#pipeWith
			 * @param callable $f
			 * @param callable[] $fArr
			 * @return Closure
			 */
			public static function pipeWith(...$args){
				return static::curryN(2, function($f, $fArr){
					return function($val) use ($f, $fArr){
						return array_reduce($fArr, function($acc, $fItem) use ($f){
							return $f($fItem, $acc);
						}, $val);
					};
				})(...$args);
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
			public static function tap(...$args){
				return static::curryN(2, function($f, $val){
					$f($val);
					return $val;
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramda-extension.firebaseapp.com/docs/#toggle
			 * @param mixed $val1
			 * @param mixed $val2
			 * @param mixed $val3
			 * @return Closure|mixed
			 */
			public static function toggle(...$args){
				return static::curryN(3, function($val1, $val2, $val3){
					if($val3 === $val1){
						return $val2;
					}
					elseif($val3 === $val2){
						return $val1;
					}

					return $val3;
				})(...$args);
			}

			/*
			 * OMITTED
			 * reason: no Promises in PHP
			 * https://ramdajs.com/docs/#then
			 */

			/**
			 * NOTE: Unlike Ramda `thunkify`, arity of `$f` must be provided as `$arity` arg.
			 * @internal Function
			 * @link https://ramdajs.com/docs/#thunkify
			 * @param int $arity
			 * @param callable $f
			 * @return Closure|mixed
			 */
			public static function thunkify(...$args){
				return static::curryN(2, function($arity, $f){
					return static::curryN($arity, function(...$params) use ($f){
						return function() use ($f, $params){
							return call_user_func_array($f, $params);
						};
					});
				})(...$args);
			}

			/**
			 * @internal Function
			 * @link https://ramdajs.com/docs/#tryCatch
			 * @param callable $tryer
			 * @param callable $catcher
			 * @return Closure
			 */
			public static function tryCatch($tryer, $catcher){
				return function(...$args) use ($tryer, $catcher){
					try{
						return call_user_func_array($tryer, $args);
					}
					catch(Exception $e){
						return call_user_func_array($catcher, $args);
					}
				};
			}

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
			 * reason: not sure if possible in PHP
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
			 * @param array $arr
			 * @return Closure
			 */
			public static function adjust(...$args){
				return static::curryN(3, function($index, $f, $arr){
					$copy = array_merge([], $arr);
					$copy[$index] = $f($copy[$index]);
					return $copy;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#all
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function all(...$args){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $item){
						if($pred($item) === false){
							return false;
						}
					}

					return true;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.allEqual
			 * @param array $arr
			 * @return Closure
			 */
			public static function allEqual(...$args){
				return static::curryN(1, function($arr){
					$val = static::head($arr);

					foreach($arr as $item){
						if($item !== $val){
							return false;
						}
					}

					return true;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.allEqualTo
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function allEqualTo(...$args){
				return static::curryN(2, function($x, $arr){
					foreach($arr as $item){
						if($item !== $x){
							return false;
						}
					}

					return true;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.allUnique
			 * @param array $arr
			 * @return Closure
			 */
			public static function allUnique(...$args){
				return static::curryN(1, function($arr){
					$u = array_values(array_unique($arr));
					return count($u) === count($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#any
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function any(...$args){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $item){
						if($pred($item) === false){
							return true;
						}
					}

					return false;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#aperture
			 * @param int $size
			 * @param array $arr
			 * @return Closure
			 */
			public static function aperture(...$args){
				return static::curryN(2, function($size, $arr){
					$len = count($arr);
					$result = [];

					for($n = 0; $n < $len; $n++){
						if(array_key_exists($n + $size - 1, $arr)){
							$nTuple = [];

							for($i = 0; $i < $size; $i++){
								$nTuple[] = $arr[$n + $i];
							}

							$result[] = $nTuple;
						}
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#append
			 * @param mixed $val
			 * @param array $arr
			 * @return Closure
			 */
			public static function append(...$args){
				return static::curryN(2, function($val, $arr){
					$copy =  array_merge([], $arr);
					$copy[] = $val;

					return $copy;
				})(...$args);
			}

			/**
			 * @internal List
			 * Get clone of array.
			 * @param array $x
			 * @return Closure
			 */
			public static function arrayClone(...$args){
				return static::curryN(1, function($x){
					return array_merge([], $x);
				})(...$args);
			}

			/**
			 * Modified version of "objOf" that returns a key/value pair array.
			 * @internal List
			 * @link https://ramdajs.com/docs/#objOf
			 * @param string $key
			 * @param mixed $val
			 * @return Closure
			 */
			public static function arrayOf(...$args){
				return static::curryN(2, function($key, $val){
					return [$key => $val];
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#chain
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function chain(...$args){
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
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#collectBy
			 * @param callable $f
			 * @param object[]|array[] $arr
			 * @return Closure
			 */
			public static function collectBy(...$args){
				return static::curryN(2, function($f, $arr){
					if(array_key_exists(0, $arr)){
						$type = gettype($arr[0]);

						if($type === 'array' || $type === 'object'){
							$group = array_reduce($arr, function($acc, $x) use ($f){
								$tag = $f($x);

								if(!isset($acc[$tag])){
									$acc[$tag] = [];
								}

								$acc[$tag][] = $x;
								return $acc;
							}, []);

							$newList = [];

							foreach($group as $val){
								$newList[] = $val;
							}

							return $newList;
						}
					}

					return $arr;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.compact
			 * @param array $arr
			 * @return Closure
			 */
			public static function compact(...$args){
				return static::curryN(1, function($arr){
					return static::filter(function($x){
						return !!$x;
					}, $arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#concat
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function concat(...$args){
				return static::curryN(2, function($x, $y){
					$validTypes = ['string', 'boolean', 'integer', 'double', 'NULL'];
					$xType = gettype($x);
					$yType = gettype($y);

					if($xType === 'array' && $yType === 'array'){
						return array_merge($x, $y);
					}
					elseif(
						(in_array($xType, $validTypes, true) || method_exists($x, '__toString')) &&
						(in_array($yType, $validTypes, true) || method_exists($y, '__toString'))
					){
						return $x . $y;
					}

					throw new Exception('Arguments cannot be coerced to type `string`.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#containsAll
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function containsAll(...$args){
				return static::curryN(2, function($x, $y){
					$p = static::pipe(static::difference($x), static::isEmpty());
					return $p($y);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#containsAny
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function containsAny(...$args){
				return static::curryN(2, function($x, $y){
					$p = static::pipe(static::intersection($x), static::isEmpty(), static::not());
					return $p($y);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#containsNone
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function containsNone(...$args){
				return static::curryN(2, function($x, $y){
					$p = static::pipe(static::intersection($x), static::isEmpty());
					return $p($y);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#concat
			 * @param string|array $x
			 * @param string|array $y
			 * @return Closure
			 */
			public static function concatRight(...$args){
				return static::curryN(2, function($x, $y){
					$xType = gettype($x);
					$yType = gettype($y);

					if($xType === $yType && $xType === 'string'){
						return $y . $x;
					}
					elseif($xType === $yType && $xType === 'array'){
						return array_merge($y, $x);
					}
					else{
						throw new Exception('Arguments must be of "string" or "array" type.');
					}
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#count
			 * @param callable $pred
			 * @param string|array|object $xs
			 * @return Closure
			 */
			public static function count(...$args){
				return static::curryN(2, function($pred, $xs){
					$type = gettype($xs);

					if($type === 'string'){
						$xs = str_split($xs);
					}
					elseif($type === 'object'){
						$xs = (array)$xs;
					}
					elseif($type !== 'array'){
						return 0;
					}

					return array_reduce($xs, function($acc, $x) use ($pred){
						return $pred($x) ? $acc + 1 : $acc;
					}, 0);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#drop
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function drop(...$args){
				return static::curryN(2, function($count, $x){
					$type = gettype($x);

					if($type === 'string'){
						return substr($x, $count, strlen($x));
					}
					elseif($type === 'array'){
						return array_slice($x, $count);
					}
					else{
						throw new Exception('Argument "$x" must be of "string" or "array" type.');
					}
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropLast
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function dropLast(...$args){
				return static::curryN(2, function($count, $x){
					$type = gettype($x);

					if($type === 'string'){
						return substr($x, 0, strlen($x) - $count);
					}
					elseif($type === 'array'){
						return array_slice($x, 0, $count + 1);
					}
					else{
						throw new Exception('Argument "$x" must be of "string" or "array" type.');
					}
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropLastWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function dropLastWhile(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);

					if($type === 'string'){
						return static::pipe('strrev', static::dropWhile($pred), 'strrev')($x);
					}
					elseif($type === 'array'){
						return static::pipe(static::arrayClone(), 'array_reverse', static::dropWhile($pred), 'array_reverse')($x);
					}
					else{
						throw new Exception('Argument "$x" must be of "string" or "array" type.');
					}
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropRepeats
			 * @param array $x
			 * @return Closure
			 */
			public static function dropRepeats(...$args){
				return static::curryN(1, function($x){
					$result = [];

					foreach($x as $val){
						if(end($result) !== $val){
							$result[] = $val;
						}
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropRepeatsWith
			 * @param callable $pred
			 * @param array $x
			 * @return Closure
			 */
			public static function dropRepeatsWith(...$args){
				return static::curryN(2, function($pred, $x){
					$result = [reset($x)];

					foreach($x as $val){
						$matches = $pred(end($result)) === $pred($val);

						if(!$matches){
							$result[] = $val;
						}
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#dropWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function dropWhile(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);

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
						return static::pipe(static::split(''), $unsetTil, static::join(''))($x);
					}
					elseif($type === 'array'){
						return static::pipe(static::arrayClone(), $unsetTil)($x);
					}
					else{
						throw new Exception('Argument "$x" must be of "string" or "array" type.');
					}
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#duplicate
			 * @param mixed $x
			 * @return Closure
			 */
			public static function duplicate(...$args){
				return static::curryN(1, function($x){
					return [$x, $x];
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#forEach
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function each(...$args){
				return static::curryN(2, function($f, $arr){
					foreach($arr as $val){
						$f($val);
					}

					return $arr;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#endsWith
			 * @param mixed $val
			 * @param string|array $x
			 * @return Closure
			 */
			public static function endsWith(...$args){
				return static::curryN(2, function($val, $x){
					$type = gettype($x);

					if($type === 'string'){
						return substr($x, -1) === $val;
					}
					elseif($type === 'array'){
						return end($x) === $val;
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#ensureArray
			 * @param mixed $x
			 * @return Closure
			 */
			public static function ensureArray(...$args){
				return static::curryN(1, function($x){
					return is_array($x) ? $x : [$x];
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#filter
			 * @param callable $pred
			 * @param string|array|object $x
			 * @return Closure
			 */
			public static function filter(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);

					if($type === 'string'){
						$result = array_filter(str_split($x), $pred);
						return implode($result);
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

					throw new Exception('Argument "$x" must be of "string", "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#find
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function find(...$args){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $val){
						if($pred($val)){
							return $val;
						}
					}

					return null;
				})(...$args);
			}

			/**
			 * @internal List
			 * Returns array of items found at provided indexes. If index doesn't exist, null is returned.
			 * @param int[]|string[] $idxs
			 * @param array $arr
			 * @return Closure
			 */
			public static function findAtIndexes(...$args){
				return static::curryN(2, function($idxs, $arr){
					return array_map(function($val) use ($arr){
						return array_key_exists($val, $arr) ? $arr[$val] : null;
					}, $idxs);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#findIndex
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function findIndex(...$args){
				return static::curryN(2, function($pred, $arr){
					for($n = 0; $n < count($arr); $n++){
						if($pred($arr[$n]) === true){
							return $n;
						}
					}

					return -1;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#findLast
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function findLast(...$args){
				return static::curryN(2, function($pred, $arr){
					for($n = count($arr) - 1; $n >= 0; $n--){
						if($pred($arr[$n]) === true){
							return $arr[$n];
						}
					}

					return -1;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#findLastIndex
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function findLastIndex(...$args){
				return static::curryN(2, function($pred, $arr){
					for($n = count($arr) - 1; $n >= 0; $n--){
						if($pred($arr[$n]) === true){
							return $n;
						}
					}

					return -1;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#findNotNil
			 * @param array $arr
			 * @return Closure
			 */
			public static function findNotNil(...$args){
				return static::curryN(1, function($arr){
					foreach($arr as $x){
						if($x !== null){
							return $x;
						}
					}

					return null;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#flatten
			 * @param array $arr
			 * @return Closure
			 */
			public static function flatten(...$args){
				return static::curryN(1, function($arr){
					$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr));
					$result = [];

					foreach($it as $val){
						$result[] = $val;
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#fromPairs
			 * @param array $arr
			 * @return Closure
			 */
			public static function fromPairs(...$args){
				return static::curryN(1, function($arr){
					$result = new stdClass();

					foreach($arr as $item){
						$key = $item[0];
						$result->$key = $item[1];
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#groupBy
			 * @param callable $keyFunc
			 * @param mixed $x
			 * @return Closure
			 */
			public static function groupBy(...$args){
				return static::curryN(2, function($keyFunc, $x){
					return static::reduce(function($acc, $val) use ($keyFunc){
						$key = $keyFunc($val);

						if(!property_exists($acc, $key)){
							$acc->$key = [];
						}

						$acc->$key[] = $val;

						return $acc;
					}, new stdClass(), $x);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#groupWith
			 * @param callable $compareFunc
			 * @param array $arr
			 * @return Closure
			 */
			public static function groupWith(...$args){
				return static::curryN(2, function($compareFunc, $arr){
					$result = [];
					$index = 0;
					$length = count($arr);

					while($index < $length){
						$nextIndex = $index + 1;

						while($nextIndex < $length && $compareFunc($arr[$nextIndex - 1], $arr[$nextIndex])){
							$nextIndex += 1;
						}

						$result[] = array_slice($arr, $index, $nextIndex - $index);
						$index = $nextIndex;
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#gtThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function gtThanLength(...$args){
				return static::curryN(2, function($length, $arr){
					return $length > count($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#gteThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function gteThanLength(...$args){
				return static::curryN(2, function($length, $arr){
					return $length >= count($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#head
			 * @param array $arr
			 * @return Closure
			 */
			public static function head(...$args){
				return static::curryN(1, function($arr){
					return reset($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#includes
			 * @param mixed $searchFor
			 * @param string|array|object $x
			 * @return Closure
			 */
			public static function includes(...$args){
				return static::curryN(2, function($searchFor, $x){
					$type = gettype($x);

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
						throw new Exception('Argument "$x" must be of "string", "object" or "array" type.');
					}
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#indexBy
			 * @param callable $keyFunc
			 * @param array $arr
			 * @return Closure
			 */
			public static function indexBy(...$args){
				return static::curryN(2, function($keyFunc, $arr){
					$result = new stdClass();

					foreach($arr as $val){
						$key = $keyFunc($val);
						$result->$key = $val;
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#indexOf
			 * @param int|string $idx
			 * @param array $arr
			 * @return Closure
			 */
			public static function indexOf(...$args){
				return static::curryN(2, function($idx, $arr){
					for($n = 0; $n < count($arr); $n++){
						if($arr[$n] === $idx){
							return $n;
						}
					}

					return -1;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#init
			 * @param array $arr
			 * @return Closure
			 */
			public static function init(...$args){
				return static::curryN(1, function($arr){
					return array_slice($arr, 0, -1);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#insert
			 * @param int $index
			 * @param mixed $val
			 * @param array $arr
			 * @return Closure
			 */
			public static function insert(...$args){
				return static::curryN(3, function($index, $val, $arr){
					$copy = array_merge([], $arr);
					array_splice($copy, $index, 0, $val);
					return $copy;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#insertAll
			 * @param int $index
			 * @param array $valArr
			 * @param array $arr
			 * @return Closure
			 */
			public static function insertAll(...$args){
				return static::curryN(3, function($index, $valArr, $arr){
					$copy = array_merge([], $arr);
					array_splice($copy, $index, 0, $valArr);
					return $copy;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#intersperse
			 * @param mixed $val
			 * @param array $arr
			 * @return Closure
			 */
			public static function intersperse(...$args){
				return static::curryN(2, function($val, $arr){
					$result = [];
					$len = count($arr);

					for($n = 0; $n < $len; $n++){
						$result[] = $arr[$n];

						if($n < $len - 1){
							$result[] = $val;
						}
					}

					return $result;
				})(...$args);
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
			public static function join(...$args){
				return static::curryN(2, function($on, $arr){
					return implode($on, $arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#last
			 * @param array $arr
			 * @return Closure
			 */
			public static function last(...$args){
				return static::curryN(1, function($arr){
					return end($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#lastIndexOf
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function lastIndexOf(...$args){
				return static::curryN(2, function($x, $arr){
					for($n = count($arr); $n > 0; $n--){
						if($arr[$n] === $x){
							return $n;
						}
					}

					return -1;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#length
			 * @param string|array|object $x
			 * @return Closure
			 */
			public static function length(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);

					if($type === 'string'){
						return strlen($x);
					}
					elseif($type === 'array'){
						return count($x);
					}
					elseif($type === 'object'){
						return count(array_keys((array)$x));
					}

					throw new Exception('Argument "$x" must be of "string", "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthEq
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthEq(...$args){
				return static::curryN(1, function($length, $x){
					$l = (gettype($x) === 'string') ? strlen($x) : count($x);
					return $l === $length;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthGt
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthGt(...$args){
				return static::curryN(1, function($length, $x){
					$l = (gettype($x) === 'string') ? strlen($x) : count($x);
					return $l > $length;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthGte
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthGte(...$args){
				return static::curryN(1, function($length, $x){
					$l = (gettype($x) === 'string') ? strlen($x) : count($x);
					return $l >= $length;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthLt
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthLt(...$args){
				return static::curryN(2, function($length, $x){
					$l = (gettype($x) === 'string') ? strlen($x) : count($x);
					return $l < $length;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthLte
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthLte(...$args){
				return static::curryN(2, function($length, $x){
					$l = (gettype($x) === 'string') ? strlen($x) : count($x);
					return $l <= $length;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.lengthNotEq
			 * @param int $length
			 * @param array|string $x
			 * @return Closure
			 */
			public static function lengthNotEq(...$args){
				return static::curryN(2, function($length, $x){
					$l = (gettype($x) === 'string') ? strlen($x) : count($x);
					return $l !== $length;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#ltThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function ltThanLength(...$args){
				return static::curryN(2, function($length, $arr){
					return $length < count($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#lteThanLength
			 * @param int $length
			 * @param array $arr
			 * @return Closure
			 */
			public static function lteThanLength(...$args){
				return static::curryN(2, function($length, $arr){
					return $length <= count($arr);
				})(...$args);
			}

			/**
			 * NOTE: This function preserves existing `Array` keys.
			 * @internal List
			 * @link https://ramdajs.com/docs/#map
			 * @param callable $mapper - receives $value, $key and $index args
			 * @param array|object|string $x
			 * @return Closure
			 */
			public static function map(...$args){
				return static::curryN(2, function($mapper, $x){
					$type = gettype($x);

					if($type === 'array'){
						$result = [];
						$index = 0;

						foreach($x as $key => $value){
							$result[$key] = $mapper($value, $key, $index);
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
						$strArr = str_split($x);
						$result = [];
						$index = 0;

						foreach($strArr as $key => $value){
							$result[] = $mapper($value, $key, $index);
							$index++;
						}

						return implode($result);
					}

					throw new Exception('Argument "$x" must be of "string", "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#mapIndexed
			 * @param callable $mapper
			 * @param array|object $x
			 * @return Closure
			 */
			public static function mapIndexed(...$args){
				return static::curryN(2, function($mapper, $x){
					$type = gettype($x);

					if($type === 'array'){
						$result = [];

						for($n = 0; $n < count($x); $n++){
							$result[] = $mapper($x[$n], $n);
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

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#mapAccum
			 * @param callable $f
			 * @param mixed $acc
			 * @param array $arr
			 * @return Closure
			 */
			public static function mapAccum(...$args){
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
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#mapAccumRight
			 * @param callable $f
			 * @param mixed $acc
			 * @param array $arr
			 * @return Closure
			 */
			public static function mapAccumRight(...$args){
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
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#mergeAll
			 * @param array $arr
			 * @return Closure
			 */
			public static function mergeAll(...$args){
				return static::curryN(1, function($arr){
					$result = [];

					foreach($arr as $obj){
						$result = array_merge($result, (array)$obj);
					}

					return (object)$result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#move
			 * @param int $from
			 * @param int $to
			 * @param array $arr
			 * @return Closure
			 */
			public static function move(...$args){
				return static::curryN(3, function($from, $to, $arr){
					$result = array_merge([], $arr);
					$length = count($result);
					$val = array_splice($result, $from, 1)[0];

					if($to < 0){
						array_splice($result, $length + $to, 0, $val);
					}
					else{
						array_splice($result, $to, 0, $val);
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * Unlike "move", this can't move to a negative index.
			 * @param int $index
			 * @param int $count
			 * @param array $arr
			 * @return Closure
			 */
			public static function moveLeft(...$args){
				return static::curryN(3, function($index, $count, $arr){
					$toInd = $index - $count;
					$newIndex = ($toInd < 0) ? 0 : $toInd;

					return static::move($index, $newIndex, $arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * Unlike "move", this can't move to an index beyond the final array index.
			 * @param int $index
			 * @param int $count
			 * @param array $arr
			 * @return Closure
			 */
			public static function moveRight(...$args){
				return static::curryN(3, function($index, $count, $arr){
					$max = count($arr) - 1;
					$toInd = $index + $count;
					$newIndex = ($toInd > $max) ? $max : $toInd;

					return static::move($index, $newIndex, $arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#none
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function none(...$args){
				return static::curryN(2, function($pred, $arr){
					foreach($arr as $item){
						if($pred($item) === true){
							return false;
						}
					}

					return true;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.notAllUnique
			 * @param array $arr
			 * @return Closure
			 */
			public static function notAllUnique(...$args){
				return static::curryN(1, function($arr){
					return !static::allUnique($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#nth
			 * @param int $index
			 * @param string|array $x
			 * @return Closure
			 */
			public static function nth(...$args){
				return static::curryN(2, function($index, $x){
					$type = gettype($x);

					if($type === 'string'){
						$sub = substr($x, $index, 1);

						return ($sub === false) ? null : $sub;
					}
					elseif($type === 'array'){
						return ($index < 0) ? $x[$index + count($x)] : $x[$index];
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.omitIndexes
			 * @param int[] $indexes
			 * @param array $arr
			 * @return Closure
			 */
			public static function omitIndexes(...$args){
				return static::curryN(2, function($indexes, $arr){
					$newArr = array_merge([], $arr);
					$arrLength = count($arr);

					foreach($indexes as $index){
						$i = ($index < 0) ? ($arrLength + $index) : $index;
						unset($newArr[$i]);
					}

					return array_values($newArr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#pair
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function pair(...$args){
				return static::curryN(2, function($x, $y){
					return [$x, $y];
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#partition
			 * @param callable $pred
			 * @param array|object $x
			 * @return Closure
			 */
			public static function partition(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);

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
								$trueArr[] = $val;
							}
							else{
								$falseArr[] = $val;
							}
						}

						return [$trueArr, $falseArr];
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.pickIndexes
			 * @param int[] $indexes
			 * @param array $arr
			 * @return Closure
			 */
			public static function pickIndexes(...$args){
				return static::curryN(2, function($indexes, $arr){
					$results = [];
					$arrLen = count($arr);

					foreach($indexes as $index){
						$i = ($index < 0) ? ($arrLen + $index) : $index;
						$results[] = $arr[$i];
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#pluck
			 * @param string $key
			 * @param mixed $x
			 * @return Closure
			 */
			public static function pluck(...$args){
				return static::curryN(2, function($key, $x){
					return static::map(static::prop($key), $x);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#prepend
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function prepend(...$args){
				return static::curryN(2, function($x, $arr){
					$copy = array_merge([], $arr);
					array_unshift($copy, $x);
					return $copy;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#range
			 * @param int $x
			 * @param int $y
			 * @return Closure
			 */
			public static function range(...$args){
				return static::curryN(2, function($x, $y){
					$results = [];

					for($n = $x; $n < $y; $n++){
						$results[] = $n;
					}

					return $results;
				})(...$args);
			}

			/**
			 * Like 'range' function but with a definable step value.
			 * Negative step value is required for descending range.
			 * @internal List
			 * @param int $start
			 * @param int $end
			 * @param int $step
			 * @return Closure
			 */
			public static function rangeStepped(...$args){
				return static::curryN(3, function($start, $end, $step){
					$result = [];
					$current = $start;

					while(($step > 0 && $current <= $end) || ($step < 0 && $current >= $end)){
						$result[] = $current;
						$current += $step;
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reduce
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param array|object|string $x
			 * @return Closure
			 */
			public static function reduce(...$args){
				return static::curryN(3, function($reducer, $defaultVal, $x){
					$type = gettype($x);

					if($type === 'object' || $type === 'array' || $type === 'string'){
						$arr = ($type === 'string') ? str_split($x) : $x;
						$acc = $defaultVal;

						foreach($arr as $key => $value){
							$acc = $reducer($acc, $value, $key);
						}

						return $acc;
					}

					throw new Exception('Argument "$x" must be of "string", "object" or "array" type.');
				})(...$args);
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
			public static function reduceBy(...$args){
				return static::curryN(4, function($valueFunc, $defaultVal, $keyFunc, $x){
					return static::reduce(function($acc, $val) use ($valueFunc, $defaultVal, $keyFunc){
						$key = $keyFunc($val);
						$acc->$key = $valueFunc(property_exists($acc, $key) ? $acc->$key : $defaultVal, $val);

						return $acc;
					}, new stdClass(), $x);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reduceRight
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param mixed $x
			 * @return Closure
			 */
			public static function reduceRight(...$args){
				return static::curryN(3, function($reducer, $defaultVal, $x){
					$type = gettype($x);
					$reducee = null;

					if($type === 'object'){
						$obj = clone $x;
						$reducee = (object)static::reverse((array)$obj);
					}
					elseif($type === 'array'){
						$reducee = static::reverse($x);
					}

					return static::reduce($reducer, $defaultVal, $reducee);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reduceWhile
			 * @param callable $pred - Takes acc and val args.
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param array|object $x
			 * @return Closure
			 */
			public static function reduceWhile(...$args){
				return static::curryN(4, function($pred, $reducer, $defaultVal, $x){
					$type = gettype($x);

					if($type === 'object'){
						$objAcc = $defaultVal;

						foreach($x as $value){
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

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reject
			 * @param callable $pred
			 * @param mixed $x
			 * @return Closure
			 */
			public static function reject(...$args){
				return static::curryN(2, function($pred, $x){
					$newPred = static::pipe($pred, static::not());
					return static::filter($newPred, $x);
				})(...$args);
			}

			/**
			 * Only applies to indexed arrays. Added because, in PHP, "array_filter" returns array with keys removed.
			 * @internal List
			 * @param array $arr
			 * @return Closure
			 */
			public static function reindex(...$args){
				return static::curryN(1, function($arr){
					return array_values($arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#rejectEq
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function rejectEq(...$args){
				return static::curryN(2, function($x, $y){
					return static::filter(function($z) use ($x){
						return $z !== $x;
					}, $y);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#rejectNil
			 * @param mixed $x
			 * @return Closure
			 */
			public static function rejectNil(...$args){
				return static::curryN(1, function($x){
					return static::filter(function($y) use ($x){
						return $y !== null;
					}, $x);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#remove
			 * @param int $start
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function remove(...$args){
				return static::curryN(3, function($start, $count, $x){
					$type = gettype($x);

					if($type === 'array'){
						$copy = array_merge([], $x);
						array_splice($copy, $start, $count);
						return $copy;
					}
					elseif($type === 'string'){
						$strArr = str_split($x);
						array_splice($strArr, $start, $count);
						return implode($strArr);
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#repeat
			 * @param mixed $val
			 * @param int $count
			 * @return Closure
			 */
			public static function repeat(...$args){
				return static::curryN(2, function($val, $count){
					return array_fill(0, $count, $val);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#reverse
			 * @param array|string $x
			 * @return Closure
			 */
			public static function reverse(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);

					if($type === 'array'){
						return static::pipe(static::arrayClone(), 'array_reverse')($x);
					}
					elseif($type === 'string'){
						return implode(array_reverse(str_split($x)));
					}

					return $x;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#scan
			 * @param callable $reducer
			 * @param mixed $defaultVal
			 * @param array $arr
			 * @return Closure
			 */
			public static function scan(...$args){
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
				})(...$args);
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
			public static function slice(...$args){
				return static::curryN(3, function($from, $to, $x){
					$type = gettype($x);

					if($type === 'array'){
						return array_slice($x, $from, $to);
					}
					elseif($type === 'string'){
						$sub = substr($x, $from, $to);
						return $sub === false ? '' : $sub;
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#sort
			 * @param callable $sorter
			 * @param array $arr
			 * @return Closure
			 */
			public static function sort(...$args){
				return static::curryN(2, function($sorter, $arr){
					$y = array_merge([], $arr);
					usort($y, $sorter);
					return $y;
				})(...$args);
			}

			/**
			 * @internal List
			 * Sorts a list of numbers from low to high.
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortNumAsc(...$args){
				return static::curryN(1, function($arr){
					return static::sort(function($a, $b){
						if($a == $b){
							return 0;
						}

						return ($a < $b) ? -1 : 1;
					}, $arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * Sorts a list of numbers from high to low.
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortNumDes(...$args){
				return static::curryN(1, function($arr){
					return static::sort(function($a, $b){
						if($a == $b){
							return 0;
						}

						return ($a < $b) ? 1 : -1;
					}, $arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#splitAt
			 * @param int $index
			 * @param string|array $x
			 * @return Closure
			 */
			public static function splitAt(...$args){
				return static::curryN(2, function($index, $x){
					$type = gettype($x);

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

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#splitEvery
			 * @param int $length
			 * @param string|array $x
			 * @return Closure
			 */
			public static function splitEvery(...$args){
				return static::curryN(2, function($length, $x){
					$type = gettype($x);

					if($type !== 'array' && $type !== 'string'){
						throw new Exception('Argument "$x" must be of "string" or "array" type.');
					}

					$result = [];
					$idx = 0;
					$count = $type === 'array' ? count($x) : strlen($x);

					while($idx < $count){
						if($type === 'array'){
							$result[] = array_slice($x, $idx, $length);
						}
						elseif($type === 'string'){
							$sub = substr($x, $idx, $length);
							$sub = ($sub === false) ? '' : $sub;
							$result[] = $sub;
						}

						$idx += $length;
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#splitWhen
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function splitWhen(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);
					/**@var array $arr */
					$arr = $x;

					if($type === 'string'){
						$arr = str_split($x);
					}

					if($type === 'array' || $type === 'string'){
						for($n = 0; $n < count($arr); $n++){
							if($pred($arr[$n]) === true){
								return static::splitAt($n, $arr);
							}
						}
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#startsWith
			 * @param array|string $prefix
			 * @param string|array $x
			 * @return Closure
			 */
			public static function startsWith(...$args){
				return static::curryN(2, function($prefix, $x){
					$type = gettype($x);

					if($type === 'string'){
						$len = strlen($prefix);
						return static::equals($prefix, static::take($len, $x));
					}
					elseif($type === 'array'){
						$len = count($prefix);
						return static::equals($prefix, static::take($len, $x));
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#tail
			 * @param string|array $x
			 * @return Closure
			 */
			public static function tail(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);

					if($type === 'string'){
						return static::slice(1, strlen($x), $x);
					}
					elseif($type === 'array'){
						return array_slice($x, 1);
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#take
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function take(...$args){
				return static::curryN(2, function($count, $x){
					$type = gettype($x);

					if($type === 'string'){
						return substr($x, 0, $count);
					}
					elseif($type === 'array'){
						return array_slice($x, 0, $count);
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#takeLast
			 * @param int $count
			 * @param string|array $x
			 * @return Closure
			 */
			public static function takeLast(...$args){
				return static::curryN(2, function($count, $x){
					$type = gettype($x);

					if($type === 'string'){
						return substr($x, 0 - $count, $count);
					}
					elseif($type === 'array'){
						return array_slice($x, 0 - $count, $count);
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#takeLastWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function takeLastWhile(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);
					/**@var array $y */
					$y = ($type === 'string') ? static::split('', $x) : $x;

					if($type === 'array' || $type === 'string'){
						$results = [];

						for($n = count($y) - 1; $n > 0; $n--){
							if($pred($y[$n]) === true){
								$results[] = $y[$n];
							}
							else{
								break;
							}
						}

						/**@var array $results */
						$results = static::reverse($results);
						return ($type === 'string') ? implode($results) : $results;
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#takeWhile
			 * @param callable $pred
			 * @param string|array $x
			 * @return Closure
			 */
			public static function takeWhile(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);
					/** @var array|string $y */
					$y = ($type === 'string') ? str_split($x) : $x;

					if($type === 'array' || $type === 'string'){
						$results = [];

						for($n = 0; $n < count($y); $n++){
							if($pred($y[$n]) === true){
								$results[] = $y[$n];
							}
							else{
								break;
							}
						}

						return ($type === 'string') ? implode($results) : $results;
					}

					throw new Exception('Argument "$x" must be of "string" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#times
			 * @param callable $f
			 * @param int $count
			 * @return Closure
			 */
			public static function times(...$args){
				return static::curryN(2, function($f, $count){
					$result = [];

					for($n = 0; $n < $count; $n++){
						$result[] = $f($n);
					}

					return $result;
				})(...$args);
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
			public static function transpose(...$args){
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

							$result[$j][] = $innerArr[$j];
							$j += 1;
						}

						$i += 1;
					}

					return $result;
				})(...$args);
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
			public static function unfold(...$args){
				return static::curryN(2, function($f, $seed){
					$pair = $f($seed);
					$result = [];

					while($pair && count($pair)){
						$result[count($result)] = $pair[0];
						$pair = $f($pair[1]);
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#uniq
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniq(...$args){
				return static::curryN(1, function($arr){
					return array_values(array_unique($arr));
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#uniqAppend
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqAppend(...$args){
				return static::curryN(2, function($x, $arr){
					return static::uniq(static::append($x, $arr));
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#uniqBy
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqBy(...$args){
				return static::curryN(2, function($f, $arr){
					$set = [];
					$result = [];

					foreach($arr as $val){
						$applied = $f($val);

						if(!in_array($val, $result, true) && !in_array($applied, $set, true)){
							$set[] = $applied;
							$result[] = $val;
						}
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#uniqPrepend
			 * @param mixed $x
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqPrepend(...$args){
				return static::curryN(2, function($x, $arr){
					return static::uniq(static::prepend($x, $arr));
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#uniqWith
			 * @param callable $pred
			 * @param array $arr
			 * @return Closure
			 */
			public static function uniqWith(...$args){
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
							$result[] = $val;
						}
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#unnest
			 * @param array $arr
			 * @return Closure
			 */
			public static function unnest(...$args){
				return static::curryN(1, function($arr){
					return static::chain(static::identity(), $arr);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#update
			 * @param int $index ;
			 * @param mixed $val ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function update(...$args){
				return static::curryN(3, function($index, $val, $arr){
					$copy = array_merge([], $arr);
					$copy[$index] = $val;

					return $copy;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#without
			 * @param array $values ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function without(...$args){
				return static::curryN(2, function($values, $arr){
					$results = [];

					foreach($arr as $item){
						if(!in_array($item, $values, true)){
							$results[] = $item;
						}
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#xPairs
			 * @param mixed $x ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function xPairs(...$args){
				return static::curryN(2, function($x, $arr){
					$results = [];

					foreach($arr as $val){
						$results[] = [$x, $val];
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramda-extension.firebaseapp.com/docs/#xPairsRight
			 * @param mixed $x ;
			 * @param array $arr
			 * @return Closure
			 */
			public static function xPairsRight(...$args){
				return static::curryN(2, function($x, $arr){
					$results = [];

					foreach($arr as $val){
						$results[] = [$val, $x];
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#xprod
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function xprod(...$args){
				return static::curryN(2, function($arr1, $arr2){
					$idx = 0;
					$iLen = count($arr1);
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
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#zip
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function zip(...$args){
				return static::curryN(2, function($arr1, $arr2){
					$rv = [];
					$idx = 0;
					$len = min(count($arr1), count($arr2));

					while($idx < $len){
						$rv[$idx] = [$arr1[$idx], $arr2[$idx]];
						$idx += 1;
					}

					return $rv;
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#zipObj
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function zipObj(...$args){
				return static::curryN(2, function($arr1, $arr2){
					$p = static::pipe(static::zip($arr2), static::fromPairs());
					return $p($arr1);
				})(...$args);
			}

			/**
			 * @internal List
			 * @link https://ramdajs.com/docs/#zipWith
			 * @param callable $f
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function zipWith(...$args){
				return static::curryN(3, function($f, $arr1, $arr2){
					$rv = [];
					$idx = 0;
					$len = min(count($arr1), count($arr2));

					while($idx < $len){
						$rv[$idx] = $f($arr1[$idx], $arr2[$idx]);
						$idx += 1;
					}

					return $rv;
				})(...$args);
			}

			//</editor-fold>

			//<editor-fold desc="LOGGING">

			/**
			 * @internal Logging
			 * @param mixed $x
			 * @return Closure
			 */
			public static function dumpVal(...$args){
				return static::curryN(1, function($x){
					return static::tap(function($x){
						var_dump($x);
					}, $x);
				})(...$args);
			}

			/**
			 * @internal Logging
			 * @param mixed $x
			 * @return Closure
			 */
			public static function echoVal(...$args){
				return static::curryN(1, function($x){
					return static::tap(function($x){
						echo $x;
					}, $x);
				})(...$args);
			}

			/**
			 * @internal Logging
			 * @param mixed $x
			 * @return Closure
			 */
			public static function printVal(...$args){
				return static::curryN(1, function($x){
					return static::tap(function($x){
						print_r($x);
					}, $x);
				})(...$args);
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
			public static function allPass(...$args){
				return static::curryN(2, function($preds, $x){
					foreach($preds as $pred){
						if($pred($x) === false){
							return false;
						}
					}

					return true;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#and
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function andTrue(...$args){
				return static::curryN(2, function($x, $y){
					return $y == true && $x == true;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#anyPass
			 * @param callable[] $preds
			 * @param mixed $x
			 * @return Closure
			 */
			public static function anyPass(...$args){
				return static::curryN(2, function($preds, $x){
					foreach($preds as $pred){
						if($pred($x) === true){
							return true;
						}
					}

					return false;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#both
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function both(...$args){
				return static::curryN(3, function($pred1, $pred2, $x){
					return $pred1($x) && $pred2($x);
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#complement
			 * @param callable $pred
			 * @param mixed $x
			 * @return Closure
			 */
			public static function complement(...$args){
				return static::curryN(2, function($pred, $x){
					return !$pred($x);
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#cond
			 * @param array[] ...$pairs
			 * @return Closure
			 */
			public static function cond(...$pairs){
				return function($val) use ($pairs){
					return array_reduce($pairs, function($acc, $fs) use ($val){
						return ($acc === null && $fs[0]($val) === true) ? $fs[1]($val) : $acc;
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
			public static function defaultTo(...$args){
				return static::curryN(2, function($val, $x){
					return isset($x) ? $x : $val;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToEmptyArray
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToEmptyArray(...$args){
				return static::curryN(1, function($x){
					return isset($x) ? $x : [];
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToEmptyObject
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToEmptyObject(...$args){
				return static::curryN(1, function($x){
					return isset($x) ? $x : new stdClass();
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToEmptyString
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToEmptyString(...$args){
				return static::curryN(1, function($x){
					return isset($x) ? $x : '';
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToFalse
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToFalse(...$args){
				return static::curryN(1, function($x){
					return isset($x) ? $x : false;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToOne
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToOne(...$args){
				return static::curryN(1, function($x){
					return isset($x) ? $x : 1;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToOne
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToTrue(...$args){
				return static::curryN(1, function($x){
					return isset($x) ? $x : true;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#defaultToZero
			 * @param mixed $x
			 * @return Closure
			 */
			public static function defaultToZero(...$args){
				return static::curryN(1, function($x){
					return isset($x) ? $x : 0;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.defaultWhen
			 * @param callable $pred
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function defaultWhen(...$args){
				return static::curryN(3, function($pred, $x, $y){
					return $pred($y) === true ? $x : $y;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#either
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function either(...$args){
				return static::curryN(3, function($pred1, $pred2, $x){
					return $pred1($x) || $pred2($x);
				})(...$args);
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
			public static function ifElse(...$args){
				return static::curryN(4, function($pred, $ifTrue, $ifFalse, $x){
					return ($pred($x) === true) ? $ifTrue($x) : $ifFalse($x);
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#isEmpty
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isEmpty(...$args){
				return static::curryN(1, function($x){
					return empty($x);
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#isFalsy
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isFalsy(...$args){
				return static::curryN(1, function($x){
					return !(boolean)$x;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#isTruthy
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isTruthy(...$args){
				return static::curryN(1, function($x){
					return (boolean)$x;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.neither
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function neither(...$args){
				return static::curryN(3, function($pred1, $pred2, $x){
					return !($pred1($x) || $pred2($x));
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.nonePass
			 * @param callable[] $preds
			 * @param mixed $x
			 * @return Closure
			 */
			public static function nonePass(...$args){
				return static::curryN(2, function($preds, $x){
					foreach($preds as $f){
						if($f($x) === true){
							return false;
						}
					}

					return true;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.nor
			 * @param callable $pred1
			 * @param callable $pred2
			 * @param mixed $x
			 * @return Closure
			 */
			public static function nor(...$args){
				return static::curryN(3, function($pred1, $pred2, $x){
					return $pred1($x) === false && $pred2($x) === false;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#not
			 * @param mixed $x
			 * @return Closure
			 */
			public static function not(...$args){
				return static::curryN(1, function($x){
					return !$x;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramda-extension.firebaseapp.com/docs/#notEqual
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function notEqual(...$args){
				return static::curryN(2, function($x, $y){
					return $x !== $y;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#or
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function orEither(...$args){
				return static::curryN(2, function($x, $y){
					return $x || $y;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#pathSatisfies
			 * @param callable $pred
			 * @param string[] $keys
			 * @param array|object $x
			 * @return Closure
			 */
			public static function pathSatisfies(...$args){
				return static::curryN(3, function($pred, $keys, $x){
					$ifElse = static::ifElse($pred, static::always(true), static::always(false));
					$p = static::pipe(static::path($keys), $ifElse);
					return $p($x);
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#propSatisfies
			 * @param callable $pred
			 * @param string $key
			 * @param array|object $x
			 * @return Closure
			 */
			public static function propSatisfies(...$args){
				return static::curryN(3, function($pred, $key, $x){
					return (bool)$pred(static::prop($key, $x));
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#unless
			 * @param callable $pred
			 * @param callable $ifFalse
			 * @param mixed $x
			 * @return Closure
			 */
			public static function unless(...$args){
				return static::curryN(3, function($pred, $ifFalse, $x){
					return $pred($x) ? $x : $ifFalse($x);
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#until
			 * @param callable $pred
			 * @param callable $do
			 * @param mixed $x
			 * @return Closure
			 */
			public static function until(...$args){
				return static::curryN(3, function($pred, $do, $x){
					$val = $x;

					while(!$pred($val)){
						$val = $do($val);
					}

					return $val;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#when
			 * @param callable $pred
			 * @param callable $ifTrue
			 * @param mixed $x
			 * @return Closure
			 */
			public static function when(...$args){
				return static::curryN(3, function($pred, $ifTrue, $x){
					return $pred($x) ? $ifTrue($x) : $x;
				})(...$args);
			}

			/**
			 * @internal Logic
			 * @link https://ramdajs.com/docs/#xor
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function xorOp(...$args){
				return static::curryN(2, function($x, $y){
					return ($x xor $y);
				})(...$args);
			}

			//</editor-fold>

			//<editor-fold desc="MATH">

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#add
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function add(...$args){
				return static::curryN(2, function($x, $y){
					return $x + $y;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.ceil
			 * @param int|float $x
			 * @return Closure
			 */
			public static function ceil(...$args){
				return static::curryN(1, function($x){
					return ceil($x);
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#dec
			 * @param int|float $x
			 * @return Closure
			 */
			public static function dec(...$args){
				return static::curryN(1, function($x){
					return $x - 1;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#divide
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function divide(...$args){
				return static::curryN(2, function($x, $y){
					return $x / $y;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.floor
			 * @param int|float $x
			 * @return Closure
			 */
			public static function floor(...$args){
				return static::curryN(1, function($x){
					return floor($x);
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#inc
			 * @param int|float $x
			 * @return Closure
			 */
			public static function inc(...$args){
				return static::curryN(1, function($x){
					return $x + 1;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#mean
			 * @param int[]|float[] $x
			 * @return Closure
			 */
			public static function mean(...$args){
				return static::curryN(1, function($x){
					$p = static::pipe(static::sum(), static::divide(count($x)));
					return $p($x);
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#median
			 * @param int[]|float[] $x
			 * @return Closure
			 */
			public static function median(...$args){
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
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#modulo
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function modulo(...$args){
				return static::curryN(2, function($x, $y){
					return $x % $y;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#multiply
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function multiply(...$args){
				return static::curryN(2, function($x, $y){
					return $x * $y;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#negate
			 * @param float|int $x
			 * @return Closure
			 */
			public static function negate(...$args){
				return static::curryN(1, function($x){
					return $x * -1;
				})(...$args);
			}

			/**
			 * @internal Math
			 * y to the power of x.
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function pow(...$args){
				return static::curryN(2, function($x, $y){
					return pow($y, $x);
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#product
			 * @param float[]|int[] $x
			 * @return Closure
			 */
			public static function product(...$args){
				return static::curryN(1, function($x){
					return static::reduce(function($acc, $num){
						return $acc * $num;
					}, 1, $x);
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.round
			 * @param float|int $x
			 * @return Closure
			 */
			public static function round(...$args){
				return static::curryN(1, function($x){
					return round($x);
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.sign
			 * @param float|int $x
			 * @return Closure
			 */
			public static function sign(...$args){
				return static::curryN(1, function($x){
					if($x < 0){
						return -1;
					}
					elseif($x > 0){
						return 1;
					}

					return 0;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#subtract
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function subtract(...$args){
				return static::curryN(2, function($x, $y){
					return $x - $y;
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://ramdajs.com/docs/#sum
			 * @param float[]|int[] $x
			 * @return Closure
			 */
			public static function sum(...$args){
				return static::curryN(1, function($x){
					return array_reduce($x, function($acc, $num){
						return $acc + $num;
					}, 0);
				})(...$args);
			}

			/**
			 * @internal Math
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.trunc
			 * @param mixed $x
			 * @return Closure
			 */
			public static function trunc(...$args){
				return static::curryN(1, function($x){
					return (int)floor($x);
				})(...$args);
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
			public static function assoc(...$args){
				return static::curryN(3, function($key, $val, $x){
					$type = gettype($x);

					if($type === 'array'){
						$result = array_merge([], $x);
						$result[$key] = $val;
						return $result;
					}
					elseif($type === 'object'){
						$result = clone $x;
						$result->$key = $val;
						return $result;
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#assocPath
			 * @param string[] $keys
			 * @param mixed $val
			 * @param object|array $x
			 * @return Closure
			 */
			public static function assocPath(...$args){
				return static::curryN(3, function($keys, $val, $x){
					$type = gettype($x);

					if($type === 'array'){
						$result = array_merge([], $x);
					}
					elseif($type === 'object'){
						$result = clone $x;
					}
					else{
						throw new Exception('Argument "$x" must be of "object" or "array" type.');
					}

					$current = &$result;
					$length = count($keys);

					for($n = 0; $n < $length; $n++){
						$type = gettype($current);

						if($type === 'array'){
							$current = &$current[$keys[$n]];
						}
						elseif($type === 'object'){
							$current = &$current->{$keys[$n]};
						}

						if($n === $length - 1){
							$current = $val;
						}
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#dissoc
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function dissoc(...$args){
				return static::curryN(2, function($key, $x){
					$type = gettype($x);

					if($type === 'array'){
						$result = array_merge([], $x);
						unset($result[$key]);
						return array_values($result);
					}
					elseif($type === 'object'){
						$result = clone $x;
						unset($result->$key);
						return $result;
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#dissocPath
			 * @param string[] $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function dissocPath(...$args){
				return static::curryN(2, function($keys, $x){
					$type = gettype($x);

					if($type === 'array'){
						$result = array_merge([], $x);
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

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#clone
			 * @param mixed $x
			 * @return Closure
			 */
			public static function deepClone(...$args){
				return static::curryN(1, function($x){
					return unserialize(serialize($x));
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#eqProps
			 * @param string $key
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function eqProps(...$args){
				return static::curryN(3, function($key, $x, $y){
					$type1 = gettype($x);
					$type2 = gettype($y);

					if($type1 === 'array' && $type2 === 'array'){
						return $x[$key] === $y[$key];
					}
					elseif($type1 === 'object' && $type2 === 'object'){
						return $x->$key === $y->$key;
					}

					return false;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#evolve
			 * @param object|array $xfs
			 * @param object|array $x
			 * @return Closure
			 */
			public static function evolve(...$args){
				return static::curryN(2, function($xfs, $x){
					$type = gettype($x);

					if($type === 'object'){
						$result = new stdClass();
						$vars = get_object_vars($x);

						foreach($vars as $key => $val){
							if(isset($xfs->{$key}) && is_callable($xfs->{$key})){
								$result->{$key} = call_user_func($xfs->{$key}, $val);
							}
							elseif(gettype($val) === 'array' || gettype($val) === 'object'){
								$result->{$key} = static::evolve($xfs->{$key}, $val);
							}
							else{
								$result->{$key} = $val;
							}
						}
					}
					elseif($type === 'array'){
						$result = [];

						foreach($x as $key => $val){
							if(isset($xfs[$key]) && is_callable($xfs[$key])){
								$result[$key] = call_user_func($xfs[$key], $val);
							}
							elseif(gettype($val) === 'array' || gettype($val) === 'object'){
								$result[$key] = static::evolve($xfs[$key], $val);
							}
							else{
								$result[$key] = $val;
							}
						}
					}
					else{
						return $x;
					}

					return $result;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#forEachObjIndexed
			 * @param string $f
			 * @param object|array $x
			 * @return Closure
			 */
			public static function forEachObjIndexed(...$args){
				return static::curryN(2, function($f, $x){
					foreach($x as $key => $value){
						$f($value, $key, $x);
					}

					return $x;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#has
			 * @param string $key
			 * @param object|array $x
			 * @return Closure|bool
			 */
			public static function has(...$args){
				return static::curryN(2, function($key, $x){
					$type = gettype($x);

					if($type === 'object'){
						return isset($x->$key);
					}
					elseif($type === 'array'){
						return array_key_exists($key, $x);
					}

					return false;
				})(...$args);
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
			public static function hasPath(...$args){
				return static::curryN(2, function($path, $x){
					$type = gettype($x);
					$counter = 0;

					if($type === 'object'){
						$subObject = clone $x;
					}
					elseif($type === 'array'){
						$subObject = array_merge([], $x);
					}
					else{
						return false;
					}

					foreach($path as $key){
						$propType = gettype($subObject);

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
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#invert
			 * @param object|array $x
			 * @return Closure
			 */
			public static function invert(...$args){
				return static::curryN(1, function($x){
					/** @var array|object $props */
					$props = static::keys($x);
					$type = gettype($x);
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
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#invertObj
			 * @param object|array $x
			 * @return Closure
			 */
			public static function invertObj(...$args){
				return static::curryN(1, function($x){
					/** @var array $props */
					$props = static::keys($x);
					$type = gettype($x);
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

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @param object|array $x
			 * @return Closure
			 */
			public static function keys(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);

					if($type === 'object'){
						return array_keys((array)$x);
					}
					elseif($type === 'array'){
						return array_keys($x);
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/*
			 * OMITTED
			 * reason: no object prototype in PHP
			 * https://ramdajs.com/docs/#keysIn
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#lens
			 * @param callable $getter
			 * @param callable $setter
			 * @return Closure
			 */
			public static function lens(...$args){
				return static::curryN(2, function($getter, $setter){
					return array(
						'getter' => $getter,
						'setter' => $setter
					);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#lensIndex
			 * @param int $x
			 * @return Closure
			 */
			public static function lensIndex(...$args){
				return static::curryN(1, function($x){
					return static::lens(static::nth($x), static::update($x));
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#lensPath
			 * @param array $x
			 * @return Closure
			 */
			public static function lensPath(...$args){
				return static::curryN(1, function($x){
					return static::lens(static::path($x), static::assocPath($x));
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#lensProp
			 * @param string $x
			 * @return Closure
			 */
			public static function lensProp(...$args){
				return static::curryN(1, function($x){
					return static::lens(static::prop($x), static::assoc($x));
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mapObjIndexed
			 * @param callable $mapper
			 * @param object|array $x
			 * @return Closure
			 */
			public static function mapObjIndexed(...$args){
				return static::curryN(2, function($mapper, $x){
					$type = gettype($x);

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

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#merge
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function merge(...$args){
				return static::curryN(2, function($x, $y){
					$type1 = gettype($x);
					$type2 = gettype($y);

					if($type1 === 'object' && $type2 === 'object'){
						return (object)array_merge((array)$x, (array)$y);
					}
					elseif($type1 === 'array' && $type2 === 'array'){
						return array_merge($x, $y);
					}

					throw new Exception('Arguments "$x" and "$y" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeDeepLeft
			 * @param object $x
			 * @param object $y
			 * @return Closure
			 */
			public static function mergeDeepLeft(...$args){
				return static::curryN(2, function($x, $y){
					return R::mergeDeepWithKey(function($k, $lVal, $rVal){
						return $lVal;
					}, $x, $y);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeDeepRight
			 * @param object $x
			 * @param object $y
			 * @return Closure
			 */
			public static function mergeDeepRight(...$args){
				return static::curryN(2, function($x, $y){
					return R::mergeDeepWithKey(function($k, $lVal, $rVal){
						return $rVal;
					}, $x, $y);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeDeepWith
			 * @param callable $f
			 * @param object $x
			 * @param object $y
			 * @return Closure
			 */
			public static function mergeDeepWith(...$args){
				return static::curryN(3, function($f, $x, $y){
					return R::mergeDeepWithKey(function($k, $lVal, $rVal) use ($f){
						return $f($lVal, $rVal);
					}, $x, $y);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeDeepWithKey
			 * @param callable $f
			 * @param object $x
			 * @param object $y
			 * @return Closure
			 */
			public static function mergeDeepWithKey(...$args){
				return static::curryN(3, function($f, $x, $y){
					return R::mergeWithKey(function($k, $lVal, $rVal) use ($f){
						if((gettype($lVal) === 'object' && gettype($rVal) === 'object')){
							return static::mergeDeepWithKey($f, $lVal, $rVal);
						}
						else{
							return $f($k, $lVal, $rVal);
						}
					}, $x, $y);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeLeft
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeLeft(...$args){
				return static::curryN(2, function($x, $y){
					$type1 = gettype($x);
					$type2 = gettype($y);

					if($type1 === 'object' && $type2 === 'object'){
						return (object)array_merge((array)$y, (array)$x);
					}
					elseif($type1 === 'array' && $type2 === 'array'){
						return array_merge($y, $x);
					}

					throw new Exception('Arguments "$x" and "$y" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeRight
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeRight(...$args){
				return static::curryN(2, function($x, $y){
					return static::merge($x, $y);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeWith
			 * @param callable $f
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeWith(...$args){
				return static::curryN(3, function($f, $x, $y){
					return static::mergeWithKey(function($_, $_l, $_r) use ($f){
						return $f($_, $_l, $_r);
					}, $x, $y);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#mergeWithKey
			 * @param callable $f
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function mergeWithKey(...$args){
				return static::curryN(3, function($f, $x, $y){
					$type1 = gettype($x);
					$type2 = gettype($y);

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
								$result[$key] = static::has($key, $y) ? $f($key, $val, $y[$key]) : $val;
							}
						}

						foreach($y as $key => $val){
							if(static::has($key, $y) && !(static::has($key, $result))){
								$result[$key] = $val;
							}
						}

						return $result;
					}

					throw new Exception('Arguments "$x" and "$y" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#modify
			 * @param string|int $key
			 * @param callable $f
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function modify(...$args){
				return static::curryN(3, function($key, $f, $obj){
					return static::modifyPath([$key], $f, $obj);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#modifyPath
			 * @param array $path
			 * @param callable $f
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function modifyPath(...$args){
				return static::curryN(3, function($path, $f, $obj){
					$type = gettype($obj);
					$val = static::path($path, $obj);

					if(($type !== 'object' && $type !== 'array') || $val === null){
						return $obj;
					}

					return static::assocPath($path, $f($val), $obj);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#objOf
			 * @param string $key
			 * @param mixed $val
			 * @return Closure
			 */
			public static function objOf(...$args){
				return static::curryN(2, function($key, $val){
					return (object)[$key => $val];
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#omit
			 * @param array $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function omit(...$args){
				return static::curryN(2, function($keys, $x){
					$type = gettype($x);

					if($type === 'object'){
						$newObj = clone $x;

						foreach($keys as $key){
							unset($newObj->$key);
						}

						return $newObj;
					}
					elseif($type === 'array'){
						$newArr = array_merge([], $x);

						foreach($keys as $key){
							unset($newArr[$key]);
						}

						return $newArr;
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.omitBy
			 * @param callable $f
			 * @param object|array $x
			 * @return Closure
			 */
			public static function omitBy(...$args){
				return static::curryN(2, function($f, $x){
					$type = gettype($x);

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
						$newArr = array_merge([], $x);
						$keys = static::keys($newArr);

						foreach($keys as $key){
							if($f($newArr[$key], $key) === true){
								unset($newArr[$key]);
							}
						}

						return $newArr;
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#over
			 * @param object $lens
			 * @param callable $f
			 * @param mixed $obj
			 * @return Closure
			 */
			public static function over(...$args){
				return static::curryN(3, function($lens, $f, $obj){
					return $lens['setter']($f($lens['getter']($obj)), $obj);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#path
			 * @param string[] $path
			 * @param object|array $x
			 * @return Closure
			 */
			public static function path(...$args){
				return static::curryN(2, function($path, $on){
					return array_reduce($path, function($acc, $x){
						if($acc === null){
							return null;
						}

						$type = gettype($acc);

						if($type === 'array'){
							return array_key_exists($x, $acc) ? $acc[$x] : null;
						}
						elseif($type === 'object'){
							return property_exists($acc, $x) ? $acc->$x : null;
						}
						else{
							return null;
						}
					}, $on);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#paths
			 * @param string[] $paths
			 * @param object|array $x
			 * @return Closure
			 */
			public static function paths(...$args){
				return static::curryN(2, function($paths, $x){
					$results = [];

					foreach($paths as $path){
						$results[] = static::path($path, $x);
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pathOr
			 * @param mixed $val
			 * @param string[] $path
			 * @param object|array $x
			 * @return Closure
			 */
			public static function pathOr(...$args){
				return static::curryN(3, function($val, $path, $x){
					return static::defaultTo($val, static::path($path, $x));
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pick
			 * @param string[] $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function pick(...$args){
				return static::curryN(2, function($keys, $x){
					$type = gettype($x);

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
						$newArr = [];

						foreach($keys as $key){
							if(array_key_exists($key, $x)){
								$newArr[$key] = $x[$key];
							}
						}

						return $newArr;
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pickAll
			 * @param string[] $keys
			 * @param mixed $defaultVal
			 * @param object|array $x
			 * @return Closure
			 */
			public static function pickAll(...$args){
				return static::curryN(3, function($keys, $defaultVal, $x){
					$type = gettype($x);

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

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#pickBy
			 * @param callable $pred
			 * @param object $x
			 * @return Closure
			 */
			public static function pickBy(...$args){
				return static::curryN(2, function($pred, $x){
					$type = gettype($x);

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

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#project
			 * @param string[] $props
			 * @param object[]|array[] $x
			 * @return Closure
			 */
			public static function project(...$args){
				return static::curryN(2, function($props, $x){
					$type = gettype($x);

					if($type !== 'array'){
						throw new Exception('Argument "$x" must be of "array" type.');
					}

					if(empty($x)){
						return [];
					}

					$type0 = gettype($x[0]);
					$results = [];

					if($type0 === 'object'){
						foreach($x as $arr){
							$newObj = new stdClass();

							foreach($props as $prop){
								$newObj->$prop = static::has($prop, $arr) ? $arr->$prop : null;
							}

							$results[] = $newObj;
						}
					}
					elseif($type0 === 'array'){
						foreach($x as $arr){
							$newArr = [];

							foreach($props as $prop){
								$newArr[$prop] = static::has($prop, $arr) ? $arr[$prop] : null;
							}

							$results[] = $newArr;
						}
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#prop
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function prop(...$args){
				return static::curryN(2, function($key, $x){
					$type = gettype($x);

					if($type === 'object'){
						return static::has($key, $x) ? $x->$key : null;
					}
					elseif($type === 'array'){
						return $x[$key];
					}

					throw new Exception('Argument "$x" must be of "object" or "array" type.');
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#propOr
			 * @param string $val
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function propOr(...$args){
				return static::curryN(3, function($val, $key, $x){
					$c = static::cond(
						[static::has($key), static::prop($key)],
						[static::always(true), static::always($val)]
					);
					return $c($x);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#props
			 * @param string[] $keys
			 * @param object|array $x
			 * @return Closure
			 */
			public static function props(...$args){
				return static::curryN(2, function($keys, $x){
					return static::map(function($key) use ($x){
						return static::prop($key, $x);
					}, $keys);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#set
			 * @param object $lens
			 * @param mixed $x
			 * @param mixed $obj
			 * @return Closure
			 */
			public static function set(...$args){
				return static::curryN(3, function($lens, $x, $obj){
					return R::over($lens, R::always($x), $obj);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#toPairs
			 * @param object|array $x
			 * @return Closure
			 */
			public static function toPairs(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);
					$keys = static::keys($x);
					$results = [];

					if($type === 'object'){
						foreach($keys as $key){
							$results[] = [$key, $x->$key];
						}
					}
					elseif($type === 'array'){
						foreach($keys as $key){
							$results[] = [$key, $x[$key]];
						}
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramda-extension.firebaseapp.com/docs/#toEntries
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function toEntries(...$args){
				return static::curryN(1, function($obj){
					$results = [];

					foreach($obj as $key => $value){
						$newObj = new stdClass();
						$newObj->$key = $value;
						$results[] = $newObj;
					}

					return $results;
				})(...$args);
			}

			/*
			 * OMITTED
			 * reason: no object prototype in PHP
			 * https://ramdajs.com/docs/#toPairsIn
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#unwind
			 * @param string $key
			 * @param object|array $x
			 * @return Closure
			 */
			public static function unwind(...$args){
				return static::curryN(2, function($key, $x){
					$type = gettype($x);

					if($type === 'array' && array_key_exists($key, $x) && is_array($x[$key])){
						$arr = $x[$key];
					}
					elseif($type === 'object' && property_exists($x, $key) && is_array($x->{$key})){
						$arr = $x->{$key};
					}
					else{
						return [$x];
					}

					return array_map(function($y) use ($key, $x){
						return static::assoc($key, $y, $x);
					}, $arr);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#values
			 * @param object|array $x
			 * @return Closure
			 */
			public static function values(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);
					$keys = static::keys($x);
					$results = [];

					if($type === 'object'){
						foreach($keys as $key){
							$results[] = $x->$key;
						}
					}
					elseif($type === 'array'){
						foreach($keys as $key){
							$results[] = $x[$key];
						}
					}

					return $results;
				})(...$args);
			}

			/*
			 * OMITTED
			 * reason: no object prototype in PHP
			 * https://ramdajs.com/docs/#valuesIn
			 */

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#view
			 * @param object $lens
			 * @param mixed $x
			 * @return Closure
			 */
			public static function view(...$args){
				return static::curryN(2, function($lens, $x){
					return $lens['getter']($x);
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#where
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function where(...$args){
				return static::curryN(2, function($x, $y){
					$type = gettype($x);
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
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#whereAny
			 * @param object|array $spec
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function whereAny(...$args){
				return static::curryN(2, function($spec, $obj){
					$type1 = gettype($spec);
					$type2 = gettype($obj);

					if($type1 === 'object' && $type2 === 'object'){
						$keys = array_keys((array)$spec);

						foreach($keys as $key){
							if(isset($obj->{$key}) && call_user_func($spec->{$key}, $obj->{$key}) === true){
								return true;
							}
						}
					}
					elseif($type1 === 'array' && $type2 === 'array'){
						$keys = array_keys($spec);

						foreach($keys as $key){
							if(isset($obj[$key]) && $spec[$key]($obj[$key]) === true){
								return true;
							}
						}
					}
					else{
						return false;
					}

					return false;
				})(...$args);
			}

			/**
			 * @internal Object
			 * @link https://ramdajs.com/docs/#whereEq
			 * @param object|array $x
			 * @param object|array $y
			 * @return Closure
			 */
			public static function whereEq(...$args){
				return static::curryN(2, function($x, $y){
					$type = gettype($x);
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
				})(...$args);
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
			public static function clamp(...$args){
				return static::curryN(3, function($min, $max, $x){
					return max($min, min($max, $x));
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#countBy
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function countBy(...$args){
				return static::curryN(2, function($f, $arr){
					return static::reduceBy(function($acc){
						return $acc + 1;
					}, 0, $f, $arr);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#difference
			 * @param array $x
			 * @param array $y
			 * @return Closure
			 */
			public static function difference(...$args){
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
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#differenceWith
			 * @param callable $f
			 * @param array $x
			 * @param array $y
			 * @return Closure
			 */
			public static function differenceWith(...$args){
				return static::curryN(3, function($f, $x, $y){
					$results = [];
					$idx = 0;
					$firstLen = count($x);

					while($idx < $firstLen){
						if(!static::_includesWith($f, $x[$idx], $y) &&
							!static::_includesWith($f, $x[$idx], $results)){
							$results[] = $x[$idx];
						}

						$idx += 1;
					}

					return $results;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#eqBy
			 * @param callable $f
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function eqBy(...$args){
				return static::curryN(3, function($f, $x, $y){
					return static::equals($f($x), $f($y));
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#equals
			 * @param mixed $x
			 * @param mixed $y
			 * @return Closure
			 */
			public static function equals(...$args){
				return static::curryN(2, function($x, $y){
					return $x === $y;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToEmptyArray
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToEmptyArray(...$args){
				return static::curryN(1, function($x){
					return $x === [];
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToEmptyObject
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToEmptyObject(...$args){
				return static::curryN(1, function($x){
					/** @var array $keys */
					$keys = static::keys($x);
					return count($keys) === 0;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToEmptyString
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToEmptyString(...$args){
				return static::curryN(1, function($x){
					return $x === '';
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToFalse
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToFalse(...$args){
				return static::curryN(1, function($x){
					return $x === false;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToNull
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToNull(...$args){
				return static::curryN(1, function($x){
					return $x === null;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToOne
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToOne(...$args){
				return static::curryN(1, function($x){
					return $x === 1;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToTrue
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToTrue(...$args){
				return static::curryN(1, function($x){
					return $x === true;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsToZero
			 * @param mixed $x
			 * @return Closure
			 */
			public static function equalsToZero(...$args){
				return static::curryN(1, function($x){
					return $x === 0;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#gt
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function gt(...$args){
				return static::curryN(2, function($x, $y){
					return $x > $y;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#gte
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function gte(...$args){
				return static::curryN(2, function($x, $y){
					return $x >= $y;
				})(...$args);
			}

			/*
			 * OMITTED
			 * reason: not sure if possible in PHP
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
			public static function innerJoin(...$args){
				return static::curryN(3, function($pred, $x, $y){
					return static::filter(function($x) use ($pred, $y){
						return static::_includesWith($pred, $x, $y);
					}, $x);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#intersection
			 * @param array $x
			 * @param array $y
			 * @return Closure
			 */
			public static function intersection(...$args){
				return static::curryN(2, function($x, $y){
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
				})(...$args);
			}

			/**
			 * @internal Relation
			 * Is number even.
			 * @param float|int $x
			 * @return Closure
			 */
			public static function isEven(...$args){
				return static::curryN(1, function($x){
					if(is_numeric($x) && gettype($x) !== 'string'){
						return $x % 2 === 0;
					}

					return false;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * Is number odd.
			 * @param float|int $x
			 * @return Closure
			 */
			public static function isOdd(...$args){
				return static::curryN(1, function($x){
					if(is_numeric($x) && gettype($x) !== 'string'){
						return $x % 2 !== 0;
					}

					return false;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#lt
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function lt(...$args){
				return static::curryN(2, function($x, $y){
					return $x < $y;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#lte
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function lte(...$args){
				return static::curryN(2, function($x, $y){
					return $x <= $y;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#max
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function max(...$args){
				return static::curryN(2, function($x, $y){
					return max($y, $x);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#maxBy
			 * @param callable $f
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function maxBy(...$args){
				return static::curryN(3, function($f, $x, $y){
					return $f($y) > $f($x) ? $y : $x;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#min
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function min(...$args){
				return static::curryN(2, function($x, $y){
					return min($y, $x);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#minBy
			 * @param callable $f
			 * @param float|int $x
			 * @param float|int $y
			 * @return Closure
			 */
			public static function minBy(...$args){
				return static::curryN(3, function($f, $x, $y){
					return $f($y) < $f($x) ? $y : $x;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#pathEq
			 * @param string[] $path
			 * @param mixed $val
			 * @param object $obj
			 * @return Closure
			 */
			public static function pathEq(...$args){
				return static::curryN(3, function($path, $val, $obj){
					$current = &$obj;

					foreach($path as $key){
						$current = &$current->$key;
					}

					return $current === $val;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.pathNotEq
			 * @param string[] $path
			 * @param mixed $val
			 * @param object $obj
			 * @return Closure
			 */
			public static function pathNotEq(...$args){
				return static::curryN(3, function($path, $val, $obj){
					$current = &$obj;

					foreach($path as $key){
						$current = &$current->$key;
					}

					return $current !== $val;
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#propEq
			 * @param string $key
			 * @param mixed $val
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function propEq(...$args){
				return static::curryN(3, function($key, $val, $obj){
					$p = static::pipe(static::prop($key), static::equals($val));
					return $p($obj);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.propNotEq
			 * @param string $key
			 * @param mixed $val
			 * @param object|array $obj
			 * @return Closure
			 */
			public static function propNotEq(...$args){
				return static::curryN(3, function($key, $val, $obj){
					$p = static::pipe(static::prop($key), static::notEqual($val));
					return $p($obj);
				})(...$args);
			}

			/**
			 * Performs a linear conversion between 2 ranges.
			 * @example R::scale(0, 100, 0, 150, 50) -> 75
			 *
			 * @internal Relation
			 * @param int|float $min1
			 * @param int|float $max1
			 * @param int|float $min2
			 * @param int|float $max2
			 * @param int|float $x
			 * @return Closure
			 */
			public static function scale(...$args){
				return static::curryN(5, function($min1, $max1, $min2, $max2, $x){
					$factor = ($max2 - $min2) / ($max1 - $min1);
					return $min2 + (($x - $min1) * $factor);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#sortBy
			 * @param callable $f
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortBy(...$args){
				return static::curryN(2, function($f, $arr){
					return static::sort(function($a, $b) use ($f){
						$aa = $f($a);
						$bb = $f($b);

						if($a == $b){
							return 0;
						}

						return ($aa < $bb) ? -1 : 1;
					}, $arr);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#sortWith
			 * @param callable[] $fArr
			 * @param array $arr
			 * @return Closure
			 */
			public static function sortWith(...$args){
				return static::curryN(2, function($fArr, $arr){
					return static::sort(function($a, $b) use ($fArr){
						$result = 0;
						$i = 0;

						while($result === 0 && $i < count($fArr)){
							$result = $fArr[$i]($a, $b);
							$i += 1;
						}

						return $result;
					}, $arr);
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#symmetricDifference
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function symmetricDifference(...$args){
				return static::curryN(2, function($arr1, $arr2){
					return static::concat(static::difference($arr1, $arr2), static::difference($arr2, $arr1));
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#symmetricDifferenceWith
			 * @param callable $pred
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function symmetricDifferenceWith(...$args){
				return static::curryN(3, function($pred, $arr1, $arr2){
					return static::concat(static::differenceWith($pred, $arr1, $arr2), static::differenceWith($pred, $arr2, $arr1));
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#union
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function union(...$args){
				return static::curryN(2, function($arr1, $arr2){
					return static::uniq(array_merge($arr1, $arr2));
				})(...$args);
			}

			/**
			 * @internal Relation
			 * @link https://ramdajs.com/docs/#unionWith
			 * @param callable $pred
			 * @param array $arr1
			 * @param array $arr2
			 * @return Closure
			 */
			public static function unionWith(...$args){
				return static::curryN(3, function($pred, $arr1, $arr2){
					return static::uniqWith($pred, array_merge($arr1, $arr2));
				})(...$args);
			}

			//</editor-fold>

			//<editor-fold desc="STRING">

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#capitalizeAll
			 * @param string $str
			 * @return Closure
			 */
			public static function capitalizeAll(...$args){
				return static::curryN(1, function($str){
					return ucwords($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#capitalizeFirst
			 * @param string $str
			 * @return Closure
			 */
			public static function capitalizeFirst(...$args){
				return static::curryN(1, function($str){
					return ucfirst($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#endsWithSuffix
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function endsWithSuffix(...$args){
				return static::curryN(2, function($str, $in){
					return (static::takeLast(strlen($str), $in) === $str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#endsWithSuffixIgnoreCase
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function endsWithSuffixIgnoreCase(...$args){
				return static::curryN(2, function($str, $in){
					/** @var string $suffix */
					$suffix = static::takeLast(strlen($str), $in);
					return strtolower($suffix) === strtolower($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#equalsStringIgnoreCase
			 * @param mixed $str1
			 * @param string $str2
			 * @return Closure
			 */
			public static function equalsStringIgnoreCase(...$args){
				return static::curryN(2, function($str1, $str2){
					return strtolower($str1) === strtolower($str2);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#match
			 * @param string $regEx
			 * @param string $str
			 * @return Closure
			 */
			public static function match(...$args){
				return static::curryN(2, function($regEx, $str){
					$results = [];
					preg_match_all($regEx, $str, $results);
					return $results[0];
				})(...$args);
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
			public static function replace(...$args){
				return static::curryN(3, function($find, $replaceWith, $str){
					$pos = strpos($str, $find);
					return ($pos !== false) ? substr_replace($str, $replaceWith, $pos, strlen($find)) : $str;
				})(...$args);
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
			public static function replaceAll(...$args){
				return static::curryN(3, function($find, $replaceWith, $str){
					return str_replace($find, $replaceWith, $str);
				})(...$args);
			}

			/**
			 * Same as "replace()" but with regex support.
			 * @internal String
			 * @param string $regEx
			 * @param string $replaceWith
			 * @param string $str
			 * @return Closure
			 */
			public static function replaceRegEx(...$args){
				return static::curryN(3, function($regEx, $replaceWith, $str){
					return preg_replace($regEx, $replaceWith, $str);
				})(...$args);
			}

			/**
			 * Replaces an index numbered token string for each item in $replaceWith.
			 * @internal String
			 * @param string $str
			 * @param string[] $replaceWith
			 * @return Closure
			 * @example R::replaceTokenStrings("last_name: {1}, first_name: {0}"), ["John", "Doe"]) => "last_name: Doe, first_name: John"
			 */
			public static function replaceTokenStrings(...$args){
				return static::curryN(2, function($replaceWith, $str){
					$strCopy = $str;

					for($n = 0; $n < count($replaceWith); $n++){
						$strCopy = implode($replaceWith[$n], explode('{' . $n . '}', $strCopy));
					}

					return $strCopy;
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#split
			 * @param string $on
			 * @param string $str
			 * @return Closure
			 */
			public static function split(...$args){
				return static::curryN(2, function($on, $str){
					return empty($on) ? str_split($str) : explode($on, $str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#startsWithPrefix
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function startsWithPrefix(...$args){
				return static::curryN(2, function($str, $in){
					return (static::take(strlen($str) - 1, $in) === $str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#startsWithPrefixIgnoreCase
			 * @param string $str
			 * @param string $in
			 * @return Closure
			 */
			public static function startsWithPrefixIgnoreCase(...$args){
				return static::curryN(2, function($str, $in){
					/** @var string $prefix */
					$prefix = static::take(strlen($str) - 1, $in);
					return strtolower($prefix) === strtolower($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#test
			 * @param string $regEx
			 * @param string $str
			 * @return Closure
			 */
			public static function test(...$args){
				return static::curryN(2, function($regEx, $str){
					return (bool)preg_match($regEx, $str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toCamelCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toCamelCase(...$args){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = ucwords(trim($str));
					$str = str_replace(" ", "", $str);
					return lcfirst($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toScreamingSnakeCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toConstCase(...$args){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = strtoupper(trim($str));
					return str_replace(" ", "_", $str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toKebabCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toKebabCase(...$args){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = trim($str);
					return str_replace(" ", "-", strtolower($str));
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#toLower
			 * @param string $str
			 * @return Closure
			 */
			public static function toLower(...$args){
				return static::curryN(1, function($str){
					return strtolower($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toPascalCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toPascalCase(...$args){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = ucwords(trim($str));
					return str_replace(" ", "", $str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toSnakeCase
			 * @param string $str
			 * @return Closure
			 */
			public static function toSnakeCase(...$args){
				return static::curryN(1, function($str){
					$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
					$str = trim($str);
					return str_replace(" ", "_", strtolower($str));
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#toString
			 * @param mixed $x
			 * @return Closure
			 */
			public static function toString(...$args){
				return static::curryN(1, function($x){
					$type = gettype($x);

					if($type === 'object' || $type === 'array'){
						return json_encode($x);
					}

					return (string)$x;
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#toUpper
			 * @param string $str
			 * @return Closure
			 */
			public static function toUpper(...$args){
				return static::curryN(1, function($str){
					return strtoupper($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramda-extension.firebaseapp.com/docs/#toUpperFirst
			 * @param string $str
			 * @return Closure
			 */
			public static function toUpperFirst(...$args){
				return static::curryN(1, function($str){
					return ucfirst($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://ramdajs.com/docs/#trim
			 * @param string $str
			 * @return Closure
			 */
			public static function trim(...$args){
				return static::curryN(1, function($str){
					return trim($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.trimEnd
			 * @param string $str
			 * @return Closure
			 */
			public static function trimEnd(...$args){
				return static::curryN(1, function($str){
					return rtrim($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.trimStart
			 * @param string $str
			 * @return Closure
			 */
			public static function trimStart(...$args){
				return static::curryN(1, function($str){
					return ltrim($str);
				})(...$args);
			}

			/**
			 * @internal String
			 * @param string[] $arr - can contain 1 or 2 items only
			 * @param string $str
			 * @return Closure
			 */
			public static function wrapWith(...$args){
				return static::curryN(2, function($arr, $str){
					return (count($arr) === 2) ? "$arr[0]$str$arr[1]" : "$arr[0]$str$arr[0]";
				})(...$args);
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
			public static function between(...$args){
				return static::curryN(3, function($min, $max, $val){
					if($val >= $min && $val <= $max){
						return true;
					}

					return false;
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isBoolean
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isBoolean(...$args){
				return static::curryN(1, function($val){
					return static::isType($val) === 'boolean';
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isArray
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isArray(...$args){
				return static::curryN(1, function($val){
					return is_array($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isFloat(...$args){
				return static::curryN(1, function($val){
					return is_float($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isFunction
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isFunction(...$args){
				return static::curryN(1, function($val){
					return is_callable($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isInt(...$args){
				return static::curryN(1, function($val){
					return is_int($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isNegative
			 * @param int|float $x
			 * @return Closure
			 */
			public static function isNegative(...$args){
				return static::curryN(1, function($x){
					return $x < 0;
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#isNil
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isNil(...$args){
				return static::curryN(1, function($val){
					return $val === null;
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isNilOrEmpty
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isNilOrEmpty(...$args){
				return static::curryN(1, function($val){
					return empty($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isNumber
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isNumber(...$args){
				return static::curryN(1, function($x){
					return is_numeric($x);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isObject
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isObject(...$args){
				return static::curryN(1, function($val){
					return is_object($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isPair
			 * @param mixed $x
			 * @return Closure
			 */
			public static function isPair(...$args){
				return static::curryN(1, function($x){
					return is_array($x) && (count($x) === 2);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isPositive
			 * @param int|float $x
			 * @return Closure
			 */
			public static function isPositive(...$args){
				return static::curryN(1, function($x){
					return $x >= 0;
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://char0n.github.io/ramda-adjunct/2.24.0/RA.html#.isSparseArray
			 * @param array $arr
			 * @return Closure
			 */
			public static function isSparseArray(...$args){
				return static::curryN(1, function($arr){
					return static::last(static::keys($arr)) > count($arr) - 1;
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramda-extension.firebaseapp.com/docs/#isString
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isString(...$args){
				return static::curryN(1, function($val){
					return is_string($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#is
			 * @param string $type - Valid values: "boolean", "integer", "double", "string", "array", "object", "resource", "NULL", "unknown type", or "resource (closed)"
			 * @param mixed $val
			 * @return Closure|string
			 */
			public static function isType(...$args){
				return static::curryN(2, function($type, $val){
					return gettype($val) === $type;
				})(...$args);
			}

			/**
			 * @internal Type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function isVarSet(...$args){
				return static::curryN(1, function($val){
					return isset($val);
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#propIs
			 * @param string $type
			 * @param string $key
			 * @param object $obj
			 * @return Closure
			 */
			public static function propIs(...$args){
				return static::curryN(3, function($type, $key, $obj){
					$p = static::pipe(static::prop($key), static::isType($type));
					return $p($obj);
				})(...$args);
			}

			/**
			 * Strips non-numeric chars and converts to either float or int.
			 * @internal Type
			 * @param string $x
			 * @return Closure
			 */
			public static function toNumber(...$args){
				return static::curryN(1, function($x){
					if(!is_string($x)){
						return 0;
					}
					elseif(strpos($x, '.') !== false){
						return floatval(preg_replace("/[^0-9.]/", '', $x));
					}
					else{
						return intval(preg_replace("/[^0-9]/", '', $x));
					}
				})(...$args);
			}

			/**
			 * Copies and casts a var to the specified type.
			 * @internal Type
			 * @param string $type
			 * @param mixed $val
			 * @return Closure
			 */
			public static function toType(...$args){
				return static::curryN(2, function($type, $val){
					$copy = $val;
					$valType = gettype($val);

					if($valType === 'object'){
						$copy = clone $val;
					}
					elseif($valType === 'array'){
						$copy = array_merge([], $val);
					}

					$success = settype($copy, $type);
					return $success === true ? $copy : null;
				})(...$args);
			}

			/**
			 * @internal Type
			 * @link https://ramdajs.com/docs/#type
			 * @param mixed $x
			 * @return Closure
			 */
			public static function type(...$args){
				return static::curryN(1, function($x){
					return gettype($x);
				})(...$args);
			}

			//</editor-fold>

			//<editor-fold desc="__INTERNAL__">

			/**
			 * @param $f
			 * @return int
			 * @throws Exception
			 */
			private static function _getArgCount($f){
				return (new ReflectionFunction($f))->getNumberOfRequiredParameters();
			}

			/**
			 * @param $pred
			 * @param $x
			 * @param $list
			 * @return bool
			 */
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

		class Placeholder{}

		R::$_ = new Placeholder();
	}

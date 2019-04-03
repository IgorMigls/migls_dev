<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

// Load the global dump() function
require_once __DIR__.'/Resources/functions/dump.php';

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class VarDumper
{
	private static $handler;

	private static $styles = [
		'default' => 'line-height:1.2em; font:14px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; position:relative; z-index:99999; word-break: normal; margin-bottom: 0; background: #fff',
		'key' => 'color: #79016f',
		'num' => 'font-weight:bold; color:#1299DA',
		'const' => 'font-weight:bold',
		'str' => 'font-weight:bold; color:#000',
		'note' => 'color:#1299DA',
		'ref' => 'color:#A0A0A0',
		'public' => 'color:#0f9600',
		'protected' => 'color:#a300bf',
		'private' => 'color:#ec0000',
		'meta' => 'color:#B729D9',
		'index' => 'color:#1299DA',
		'ellipsis' => 'color:#FF8400',
	];

	public static function dump($var)
	{
		$htmlDumper = new HtmlDumper();
		$htmlDumper->setStyles(static::$styles);
		if (null === self::$handler){
			$cloner = new VarCloner();
			$dumper = 'cli' === PHP_SAPI ? new CliDumper() : $htmlDumper;
			self::$handler = function ($var) use ($cloner, $dumper) {
				$dumper->dump($cloner->cloneVar($var));
			};
		}

		return call_user_func(self::$handler, $var);
	}

	public static function setHandler(callable $callable = null)
	{
		$prevHandler = self::$handler;
		self::$handler = $callable;

		return $prevHandler;
	}

	/**
	 * @method setStyles - set param Styles
	 * @param array $styles
	 */
	public static function setStyles($styles)
	{
		self::$styles = $styles + self::$styles;
	}
}

<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Engine;

use Decoda\Exception\IoException;

/**
 * Renders tags by using PHP as template engine.
 */
class PhpEngine extends AbstractEngine {

	/**
	 * Renders the tag by using PHP templates.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 * @throws \Decoda\Exception\IoException
	 */
	public function render(array $tag, $content) {
		$setup = $this->getFilter()->getTag($tag['tag']);
		$path = $this->getPath() . $setup['template'] . '.php';

		if (!file_exists($path)) {
			throw new IoException(sprintf('Template file %s does not exist', $setup['template']));
		}

		extract($tag['attributes'], EXTR_SKIP);
		ob_start();

		include $path;

		return trim(ob_get_clean());
	}

}
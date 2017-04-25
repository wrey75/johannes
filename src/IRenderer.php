<?php

namespace Johannes;

/**
 * A renderer. All renderers (for the different parts of the
 * application) should be a render.
 * 
 * @author wrey75
 *
 */
interface IRenderer
{
	/**
	 * 
	 * @param string $text the text to render.
	 * @param \Mustache_LambdaHelper $helper the mustache helper to
	 * render the text.
	 */
	public function render($text, $helper);
}


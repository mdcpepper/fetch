<?php
namespace Craft;

/**
 * Fetch plugin class
 *
 * @author    Mike Pepper, Enovate Design Ltd <mike.pepper@enovate.co.uk>
 * @copyright Copyright (c) 2015, Enovate Design, Ltd.
 * @since     0.1.0
 */
class FetchPlugin extends BasePlugin implements IPlugin
{
	/**
	 * Get the plugin name
	 *
	 * @return string The plugin name
	 */
	public function getName()
	{
		return Craft::t('Fetch');
	}

	/**
	 * Get the plugin version
	 *
	 * @return string The current version of the plugin
	 */
	public function getVersion()
	{
		return '0.1.0';
	}

	/**
	 * Get the plugin developer name
	 *
	 * @return string The developer name
	 */
	public function getDeveloper()
	{
		return 'Enovate Design';
	}

	/**
	 * Get the plugin developer url
	 *
	 * @return string The developer URL
	 */
	public function getDeveloperUrl()
	{
		return 'http://www.enovate.co.uk';
	}

	/**
	 * Returns true if the plugin should have a CP section link, false if not.
	 *
	 * @return boolean
	 */
	public function hasCpSection()
	{
		return false;
	}
}
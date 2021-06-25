<?php declare (strict_types = 1);

namespace Buckaroo\SDK\Helpers;

use SimpleXMLElement;

/**
 * Read the plugin.xml file
 */
class PluginInfo
{
    /**
     * @var SimpleXMLElement
     */
    protected $info;

    /**
     * @return SimpleXMLElement
     */
    protected function readPluginXml()
    {
        if (empty($this->info)) {
            $this->info = new SimpleXMLElement(file_get_contents(__DIR__ . '/../plugin.xml'));
        }

        return $this->info;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->readPluginXml()->label;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->readPluginXml()->version;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->readPluginXml()->author;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->readPluginXml()->link;
    }
}

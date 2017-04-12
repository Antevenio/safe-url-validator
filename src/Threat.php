<?php

namespace Antevenio\SafeUrl;

class Threat
{
    protected $url;
    protected $type;
    protected $platform;

    /**
     * @param mixed $url
     * @return Threat
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param mixed $type
     * @return Threat
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param mixed $platform
     * @return Threat
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getType() {
        return $this->type;
    }

    public function getPlatform() {
        return $this->platform;
    }
}
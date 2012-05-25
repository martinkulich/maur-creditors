<?php

class RequestService
{
    protected $slug = null;

    public function getSlug()
    {
        if(is_null($this->slug))
        {
            $host = $_SERVER['HTTP_HOST'];
            $parts = explode('.', $host);
            $firstPart = reset($parts);
            if($firstPart == 'www')
            {
                $keys = array_keys($parts);
                $firstKey = reset($keys);
                unset($parts[$firstKey]);
            }
            if(count($parts)>= 2)
            {
                $this->slug = reset($parts);
            }
        }

        return $this->slug;
    }
}

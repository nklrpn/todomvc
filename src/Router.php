<?php
namespace App;

class Router
{
    /**
     * @var string
     */
    protected $uri;
    
    /**
     * @var array
     */
    protected $query;
    
    /**
     * @param string $uri
     * @return array
     */
    public function resolve($uri = null)
    {
        $uri = ($uri) ? $uri : $_SERVER['REQUEST_URI'];
        $this->uri = $uri;

        return $this->getQuery();
    }

    /**
     * @return array
     */
    private function getQuery()
    {
        $uriPath = parse_url($this->uri, PHP_URL_PATH);

        if (!$uriPath || strlen($uriPath) < 2) {
            $pathParts = ['/'];
        } else {
            $pathParts = array_values(
                array_filter(
                    explode('/', $uriPath)
                )
            );
        }

        $route = array_shift($pathParts);

        $params = [];
        if (count($pathParts) % 2 == 0) {
            for ($i = 0; $i < count($pathParts); $i+=2) {
                $params[urldecode($pathParts[$i])] = urldecode($pathParts[$i+1]);
            }
        }

        $query = [
            'route' => $route,
            'params' => $params,
        ];
        
        $this->query = $query;
        
        return $query;
    }
}
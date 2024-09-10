
# PSR-6 NullObject cache

[![License](https://poser.pugx.org/thadafinser/psr6-null-cache/license)](https://packagist.org/packages/thadafinser/psr6-null-cache) 

Fork of ["The missing PSR-6 NullObject implementation."](https://github.com/ThaDafinser/psr6-null-cache) without the in-memory adapter.

Differences between the two packages:
* Only null cache in this package (removed `MemoryCacheItemPool`)
* added ci/cd github action (enforce code coverage)
* added taskfile for running the tests locally through a docker image

You can use this package, when you want to
 - avoid using `null` check logic, read more [here](http://designpatternsphp.readthedocs.org/en/latest/Behavioral/NullObject/README.html)
 - need a fake cache implementation for testing
 
## Install

```shell
composer require --dev seanja/null-cache
```

## Example / usage

Before this package, you needed to allow `null` as a parameter, if you wanted to avoid a package dependency to a specific `PSR-6 cache implementation`


### Old code

```php
namespace MyPackage;

use Psr\Cache\CacheItemPoolInterface;

class MyCode
{

    public function __construct(CacheItemPoolInterface $cache = null)
    {
        $this->cache = $cache;
    }

    /**
     * Can return an instance of null, which is bad!
     *
     * @return null CacheItemPoolInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    private function internalHeavyMethod()
    {
        $cacheKey = 'myKey';
        
        // you need to check first, if there is a cache instance around
        if ($this->getCache() !== null && $this->getCache()->hasItem($cacheKey) === true) {
            // cache is available + it has a cache hit!
            return $this->getCache()->getItem($cacheKey);
        }
        
        $result = do_something_heavy();
        
        // you need to check first, if there is a cache instance around
        if ($this->getCache() !== null) {
            $item = $this->getCache()->getItem($cacheKey);
            $item->set($result);
            $this->getCache()->save($item);
        }
        
        return $result;
    }
}

```

### New code

```php
namespace MyPackage;

use Psr\Cache\CacheItemPoolInterface;
use SeanJA\NullCache\Adapter\NullCacheItemPool;

class MyCode
{

    /**
     * You could require a cache instance, so you can remove the null check in __construct() as well
     * 
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(CacheItemPoolInterface $cache = null)
    {
        if($cache === null){
            $cache = new NullCacheItemPool();
        }
        
        $this->cache = $cache;
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    private function internalHeavyMethod()
    {
        $cacheKey = 'myKey';
        
        if ($this->getCache()->hasItem($cacheKey) === true) {
            // cache is available + it has a cache hit!
            return $this->getCache()->getItem($cacheKey);
        }
        
        $result = do_something_heavy();
        
        $item = $this->getCache()->getItem($cacheKey);
        $item->set($result);
        $this->getCache()->save($item);
        
        return $result;
    }
}
```

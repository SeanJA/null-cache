<?php
namespace Tests\SeanJA\NullCache\Adapter;

use PHPUnit\Framework\TestCase;
use SeanJA\NullCache\CacheItem;
use SeanJA\NullCache\Adapter\NullCacheItemPool;

class NullCacheItemPoolTest extends TestCase
{

    public function testGetItem()
    {
        $pool = new NullCacheItemPool();
        
        $item = $pool->getItem('myKey');
        
        $this->assertInstanceOf(CacheItem::class, $item);
        $this->assertEquals('myKey', $item->getKey());
        // not from cache -> new object, so false!
        $this->assertFalse($item->isHit());
        $this->assertNull($item->get());
    }

    public function testGetItems()
    {
        $pool = new NullCacheItemPool();
        
        $items = $pool->getItems([
            'myKey',
            'myKey2'
        ]);
        
        $this->assertIsArray($items);
        $this->assertCount(2, $items);
        
        $this->assertArrayHasKey('myKey', $items);
        $this->assertArrayHasKey('myKey2', $items);
        
        $this->assertInstanceOf(CacheItem::class, $items['myKey']);
        $this->assertInstanceOf(CacheItem::class, $items['myKey2']);
    }

    public function testHasItem()
    {
        $pool = new NullCacheItemPool();
        
        $this->assertFalse($pool->hasItem('myKey'));
    }

    public function testClear()
    {
        $pool = new NullCacheItemPool();
        
        $this->assertTrue($pool->clear());
    }

    public function testDeleteItem()
    {
        $pool = new NullCacheItemPool();
        
        $this->assertTrue($pool->deleteItem('myKey'));
    }

    public function testDeleteItems()
    {
        $pool = new NullCacheItemPool();
        
        $this->assertTrue($pool->deleteItems([]));
        
        $this->assertTrue($pool->deleteItems([
            'myKey',
            'myKey2'
        ]));
    }

    public function testSave()
    {
        $pool = new NullCacheItemPool();
        
        $item = new CacheItem('myKey', null, false);
        
        $this->assertTrue($pool->save($item));
    }

    public function testSaveDeferred()
    {
        $pool = new NullCacheItemPool();
        
        $item = new CacheItem('myKey', null, false);
        
        $this->assertTrue($pool->saveDeferred($item));
    }

    public function testCommit()
    {
        $pool = new NullCacheItemPool();
        
        $this->assertTrue($pool->commit());
    }
}

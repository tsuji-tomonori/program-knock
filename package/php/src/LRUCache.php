<?php

declare(strict_types=1);

namespace ProgramKnock;

class LRUCache
{
    private int $capacity;
    private array $cache;
    private array $order;

    /**
     * LRUキャッシュを初期化
     *
     * @param int $capacity キャッシュの最大容量
     */
    public function __construct(int $capacity)
    {
        $this->capacity = $capacity;
        $this->cache = [];
        $this->order = [];
    }

    /**
     * キーに対応する値を取得
     *
     * @param int $key 検索するキー
     * @return int キーに対応する値、または -1
     */
    public function get(int $key): int
    {
        if (!isset($this->cache[$key])) {
            return -1;
        }

        // 最近使用されたものとして更新
        $this->updateOrder($key);

        return $this->cache[$key];
    }

    /**
     * キーと値のペアを格納
     *
     * @param int $key 格納するキー
     * @param int $value 格納する値
     */
    public function put(int $key, int $value): void
    {
        if (isset($this->cache[$key])) {
            // 既存のキーの値を更新
            $this->cache[$key] = $value;
            $this->updateOrder($key);
        } else {
            // 新しいキーを追加
            if (count($this->cache) >= $this->capacity) {
                // 容量を超える場合、最も古いキーを削除
                $oldestKey = array_shift($this->order);
                unset($this->cache[$oldestKey]);
            }

            $this->cache[$key] = $value;
            $this->order[] = $key;
        }
    }

    /**
     * キーの使用順序を更新
     *
     * @param int $key 更新するキー
     */
    private function updateOrder(int $key): void
    {
        // キーを順序リストから削除
        $index = array_search($key, $this->order, true);
        if ($index !== false) {
            array_splice($this->order, $index, 1);
        }

        // キーを最後（最新）に追加
        $this->order[] = $key;
    }
}

<?php

declare(strict_types=1);

namespace ProgramKnock;

class ScopeManager
{
    /**
     * スコープのスタック（各スコープは変数名=>値の配列）
     * @var array<array<string, int>>
     */
    private array $scopeStack;

    public function __construct()
    {
        // グローバルスコープから開始
        $this->scopeStack = [[]];
    }

    /**
     * 新しいスコープに入る
     */
    public function enterScope(): void
    {
        $this->scopeStack[] = [];
    }

    /**
     * 現在のスコープから出る
     */
    public function exitScope(): void
    {
        // グローバルスコープの場合は何もしない
        if (count($this->scopeStack) > 1) {
            array_pop($this->scopeStack);
        }
    }

    /**
     * 現在のスコープに変数を設定
     */
    public function setVariable(string $name, int $value): void
    {
        $currentScopeIndex = count($this->scopeStack) - 1;
        $this->scopeStack[$currentScopeIndex][$name] = $value;
    }

    /**
     * 変数の値を取得（内側から外側のスコープに向かって検索）
     */
    public function getVariable(string $name): ?int
    {
        // 内側のスコープから外側のスコープに向かって検索
        for ($i = count($this->scopeStack) - 1; $i >= 0; $i--) {
            if (isset($this->scopeStack[$i][$name])) {
                return $this->scopeStack[$i][$name];
            }
        }

        return null;
    }

    /**
     * 現在のスコープレベルを取得（テスト用）
     */
    public function getCurrentScopeLevel(): int
    {
        return count($this->scopeStack) - 1;
    }

    /**
     * 現在のスコープの変数一覧を取得（テスト用）
     *
     * @return array<string, int>
     */
    public function getCurrentScopeVariables(): array
    {
        return $this->scopeStack[count($this->scopeStack) - 1];
    }
}

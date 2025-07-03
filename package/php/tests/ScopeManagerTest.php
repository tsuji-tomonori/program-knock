<?php

declare(strict_types=1);

namespace ProgramKnock\Tests;

use ProgramKnock\ScopeManager;

class ScopeManagerTest
{
    private function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \AssertionError($message ?: "Expected " . json_encode($expected) . ", but got " . json_encode($actual));
        }
    }

    private function assertNull($actual, $message = ''): void
    {
        if ($actual !== null) {
            throw new \AssertionError($message ?: "Expected null, but got " . json_encode($actual));
        }
    }

    public function testBasicVariableOperations(): void
    {
        $scope = new ScopeManager();

        // グローバルスコープに変数を設定
        $scope->setVariable('x', 10);
        $this->assertEquals(10, $scope->getVariable('x'));

        // 存在しない変数
        $this->assertNull($scope->getVariable('y'));
    }

    public function testSingleScopeEntry(): void
    {
        $scope = new ScopeManager();

        $scope->enterScope();
        $scope->setVariable('a', 5);
        $this->assertEquals(5, $scope->getVariable('a'));

        $scope->exitScope();
        $this->assertNull($scope->getVariable('a'));
    }

    public function testVariableShadowing(): void
    {
        $scope = new ScopeManager();

        // グローバルスコープに変数設定
        $scope->setVariable('x', 10);
        $this->assertEquals(10, $scope->getVariable('x'));

        // 新しいスコープで同じ名前の変数を設定
        $scope->enterScope();
        $scope->setVariable('x', 20);
        $this->assertEquals(20, $scope->getVariable('x'));

        // スコープを出ると元の値に戻る
        $scope->exitScope();
        $this->assertEquals(10, $scope->getVariable('x'));
    }

    public function testNestedScopes(): void
    {
        $scope = new ScopeManager();

        $scope->setVariable('global', 1);

        $scope->enterScope();
        $scope->setVariable('level1', 2);

        $scope->enterScope();
        $scope->setVariable('level2', 3);

        // 全てのレベルの変数にアクセス可能
        $this->assertEquals(1, $scope->getVariable('global'));
        $this->assertEquals(2, $scope->getVariable('level1'));
        $this->assertEquals(3, $scope->getVariable('level2'));

        $scope->exitScope();
        // level2は削除される
        $this->assertEquals(1, $scope->getVariable('global'));
        $this->assertEquals(2, $scope->getVariable('level1'));
        $this->assertNull($scope->getVariable('level2'));

        $scope->exitScope();
        // level1も削除される
        $this->assertEquals(1, $scope->getVariable('global'));
        $this->assertNull($scope->getVariable('level1'));
        $this->assertNull($scope->getVariable('level2'));
    }

    public function testGlobalScopeProtection(): void
    {
        $scope = new ScopeManager();

        $scope->setVariable('global', 100);

        // グローバルスコープでexitScope()を呼んでも何も起こらない
        $scope->exitScope();
        $this->assertEquals(100, $scope->getVariable('global'));

        // 複数回呼んでも同様
        $scope->exitScope();
        $scope->exitScope();
        $this->assertEquals(100, $scope->getVariable('global'));
    }

    public function testComplexShadowing(): void
    {
        $scope = new ScopeManager();

        $scope->setVariable('x', 1);
        $scope->setVariable('y', 2);

        $scope->enterScope();
        $scope->setVariable('x', 10); // xをシャドウ
        $scope->setVariable('z', 30);

        $scope->enterScope();
        $scope->setVariable('y', 200); // yをシャドウ

        $this->assertEquals(10, $scope->getVariable('x')); // 中間スコープのx
        $this->assertEquals(200, $scope->getVariable('y')); // 内側スコープのy
        $this->assertEquals(30, $scope->getVariable('z')); // 中間スコープのz

        $scope->exitScope();
        $this->assertEquals(10, $scope->getVariable('x')); // 中間スコープのx
        $this->assertEquals(2, $scope->getVariable('y')); // グローバルスコープのy
        $this->assertEquals(30, $scope->getVariable('z')); // 中間スコープのz

        $scope->exitScope();
        $this->assertEquals(1, $scope->getVariable('x')); // グローバルスコープのx
        $this->assertEquals(2, $scope->getVariable('y')); // グローバルスコープのy
        $this->assertNull($scope->getVariable('z')); // 削除済み
    }

    public function testVariableOverwrite(): void
    {
        $scope = new ScopeManager();

        $scope->setVariable('x', 5);
        $this->assertEquals(5, $scope->getVariable('x'));

        // 同じスコープで上書き
        $scope->setVariable('x', 15);
        $this->assertEquals(15, $scope->getVariable('x'));

        $scope->enterScope();
        $scope->setVariable('x', 25);
        $this->assertEquals(25, $scope->getVariable('x'));

        // 同じスコープで上書き
        $scope->setVariable('x', 35);
        $this->assertEquals(35, $scope->getVariable('x'));
    }

    public function testManyNestedScopes(): void
    {
        $scope = new ScopeManager();

        $depth = 10;

        // 10レベルのスコープを作成
        for ($i = 0; $i < $depth; $i++) {
            $scope->enterScope();
            $scope->setVariable("var$i", $i * 10);
        }

        // 全ての変数にアクセス可能
        for ($i = 0; $i < $depth; $i++) {
            $this->assertEquals($i * 10, $scope->getVariable("var$i"));
        }

        // スコープを一つずつ出る
        for ($i = $depth - 1; $i >= 0; $i--) {
            $scope->exitScope();

            // 現在のレベルより深い変数は削除される
            for ($j = 0; $j < $depth; $j++) {
                if ($j < $i) {
                    $this->assertEquals($j * 10, $scope->getVariable("var$j"));
                } else {
                    $this->assertNull($scope->getVariable("var$j"));
                }
            }
        }
    }

    public function testScopeLevel(): void
    {
        $scope = new ScopeManager();

        $this->assertEquals(0, $scope->getCurrentScopeLevel());

        $scope->enterScope();
        $this->assertEquals(1, $scope->getCurrentScopeLevel());

        $scope->enterScope();
        $this->assertEquals(2, $scope->getCurrentScopeLevel());

        $scope->exitScope();
        $this->assertEquals(1, $scope->getCurrentScopeLevel());

        $scope->exitScope();
        $this->assertEquals(0, $scope->getCurrentScopeLevel());

        // グローバルスコープでexit
        $scope->exitScope();
        $this->assertEquals(0, $scope->getCurrentScopeLevel());
    }

    public function testCurrentScopeVariables(): void
    {
        $scope = new ScopeManager();

        $scope->setVariable('global1', 10);
        $scope->setVariable('global2', 20);

        $globalVars = $scope->getCurrentScopeVariables();
        $this->assertEquals(['global1' => 10, 'global2' => 20], $globalVars);

        $scope->enterScope();
        $scope->setVariable('local1', 30);
        $scope->setVariable('local2', 40);

        $localVars = $scope->getCurrentScopeVariables();
        $this->assertEquals(['local1' => 30, 'local2' => 40], $localVars);
    }

    public function testNegativeValues(): void
    {
        $scope = new ScopeManager();

        $scope->setVariable('negative', -100);
        $this->assertEquals(-100, $scope->getVariable('negative'));

        $scope->enterScope();
        $scope->setVariable('negative', -200);
        $this->assertEquals(-200, $scope->getVariable('negative'));

        $scope->exitScope();
        $this->assertEquals(-100, $scope->getVariable('negative'));
    }

    public function testZeroValues(): void
    {
        $scope = new ScopeManager();

        $scope->setVariable('zero', 0);
        $this->assertEquals(0, $scope->getVariable('zero'));

        $scope->enterScope();
        $scope->setVariable('zero', 0);
        $this->assertEquals(0, $scope->getVariable('zero'));
    }

    public function testLargeValues(): void
    {
        $scope = new ScopeManager();

        $largeValue = 1000000;
        $scope->setVariable('large', $largeValue);
        $this->assertEquals($largeValue, $scope->getVariable('large'));
    }

    public function testManyVariablesInScope(): void
    {
        $scope = new ScopeManager();

        // 100個の変数を設定
        for ($i = 0; $i < 100; $i++) {
            $scope->setVariable("var$i", $i);
        }

        // 全て取得可能
        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals($i, $scope->getVariable("var$i"));
        }
    }

    public function testComplexScenario(): void
    {
        $scope = new ScopeManager();

        // グローバルスコープ
        $scope->setVariable('a', 1);
        $scope->setVariable('b', 2);

        $scope->enterScope(); // レベル1
        $scope->setVariable('b', 20);
        $scope->setVariable('c', 3);

        $scope->enterScope(); // レベル2
        $scope->setVariable('a', 100);
        $scope->setVariable('d', 4);

        // 変数の確認
        $this->assertEquals(100, $scope->getVariable('a')); // レベル2でシャドウ
        $this->assertEquals(20, $scope->getVariable('b'));  // レベル1でシャドウ
        $this->assertEquals(3, $scope->getVariable('c'));   // レベル1
        $this->assertEquals(4, $scope->getVariable('d'));   // レベル2

        $scope->exitScope(); // レベル1に戻る
        $this->assertEquals(1, $scope->getVariable('a'));   // グローバル
        $this->assertEquals(20, $scope->getVariable('b'));  // レベル1
        $this->assertEquals(3, $scope->getVariable('c'));   // レベル1
        $this->assertNull($scope->getVariable('d'));        // 削除済み

        $scope->exitScope(); // グローバルに戻る
        $this->assertEquals(1, $scope->getVariable('a'));   // グローバル
        $this->assertEquals(2, $scope->getVariable('b'));   // グローバル
        $this->assertNull($scope->getVariable('c'));        // 削除済み
        $this->assertNull($scope->getVariable('d'));        // 削除済み
    }

    public function testEmptyVariableNames(): void
    {
        $scope = new ScopeManager();

        // 空文字列の変数名でもOK
        $scope->setVariable('', 999);
        $this->assertEquals(999, $scope->getVariable(''));
    }

    public function testLongVariableNames(): void
    {
        $scope = new ScopeManager();

        $longName = str_repeat('a', 1000);
        $scope->setVariable($longName, 123);
        $this->assertEquals(123, $scope->getVariable($longName));
    }
}

<?php

declare(strict_types=1);

namespace ProgramKnock;

class Reservation
{
    public function __construct(
        public int $roomId,
        public int $startTime,
        public int $endTime
    ) {}
}

class MeetingRoomReservation
{
    /**
     * 部屋ごとの予約リスト
     * @var array<int, array<Reservation>>
     */
    private array $reservations = [];

    /**
     * 予約をリクエスト
     *
     * @param int $roomId 部屋ID
     * @param int $startTime 開始時間
     * @param int $endTime 終了時間
     * @return bool 予約可能ならtrue、不可能ならfalse
     */
    public function requestReservation(int $roomId, int $startTime, int $endTime): bool
    {
        // 部屋の予約リストを初期化（初回の場合）
        if (!isset($this->reservations[$roomId])) {
            $this->reservations[$roomId] = [];
        }

        // 既存の予約と重複チェック
        foreach ($this->reservations[$roomId] as $reservation) {
            if ($this->hasOverlap($startTime, $endTime, $reservation->startTime, $reservation->endTime)) {
                return false; // 重複があるため予約不可
            }
        }

        // 重複がないため予約を追加
        $this->reservations[$roomId][] = new Reservation($roomId, $startTime, $endTime);
        return true;
    }

    /**
     * 時間の重複をチェック
     *
     * @param int $start1 時間範囲1の開始
     * @param int $end1 時間範囲1の終了
     * @param int $start2 時間範囲2の開始
     * @param int $end2 時間範囲2の終了
     * @return bool 重複があるならtrue、なければfalse
     */
    private function hasOverlap(int $start1, int $end1, int $start2, int $end2): bool
    {
        // 重複しない場合: end1 <= start2 または end2 <= start1
        // つまり重複する場合: !(end1 <= start2 || end2 <= start1)
        return !($end1 <= $start2 || $end2 <= $start1);
    }

    /**
     * 複数の予約リクエストを一括処理
     *
     * @param array<array{0: int, 1: int, 2: int}> $requests 予約リクエストリスト
     * @return array<bool> 各リクエストの結果
     */
    public static function processReservations(array $requests): array
    {
        $system = new self();
        $results = [];

        foreach ($requests as $request) {
            [$roomId, $startTime, $endTime] = $request;
            $results[] = $system->requestReservation($roomId, $startTime, $endTime);
        }

        return $results;
    }

    /**
     * 指定部屋の予約一覧を取得（テスト用）
     *
     * @param int $roomId 部屋ID
     * @return array<Reservation> 予約一覧
     */
    public function getReservations(int $roomId): array
    {
        return $this->reservations[$roomId] ?? [];
    }

    /**
     * 全予約をクリア（テスト用）
     */
    public function clearAll(): void
    {
        $this->reservations = [];
    }
}

<?php

namespace App\Domain;

use App\Models\Resource;
use Illuminate\Support\Collection;

/**
 * Service responsible for allocating tables optimally for restaurant bookings
 * Implements algorithms to minimize the number of tables used and excess capacity
 */
class TableAllocator
{
    /**
     * Allocate tables for a restaurant booking
     *
     * @param Collection $availableTables Available tables
     * @param int $partySize Required party size
     * @return Collection<Resource> Allocated tables
     * @throws \Exception If no suitable allocation can be found
     */
    public function allocateTables(Collection $availableTables, int $partySize): Collection
    {
        if ($availableTables->isEmpty()) {
            throw new \Exception(__('api.availability.no_slots'));
        }

        // Try to find a single table that can accommodate the party
        $singleTable = $this->findSingleTable($availableTables, $partySize);
        if ($singleTable) {
            return collect([$singleTable]);
        }

        // Try to find a combination of tables
        $combination = $this->findTableCombination($availableTables, $partySize);
        if ($combination->isNotEmpty()) {
            return $combination;
        }

        throw new \Exception(__('api.availability.no_slots'));
    }

    /**
     * Find a single table that can accommodate the party size
     */
    private function findSingleTable(Collection $availableTables, int $partySize): ?Resource
    {
        return $availableTables
            ->filter(function ($table) use ($partySize) {
                return $table->capacity >= $partySize;
            })
            ->sortBy('capacity')
            ->first();
    }

    /**
     * Find a combination of tables that can accommodate the party size
     */
    private function findTableCombination(Collection $availableTables, int $partySize): Collection
    {
        $tables = $availableTables->sortBy('capacity')->values();
        $combinations = $this->generateCombinations($tables, $partySize);
        
        if ($combinations->isEmpty()) {
            return collect();
        }

        // Sort combinations by preference:
        // 1. Fewer tables
        // 2. Less excess capacity
        return $combinations->sortBy([
            ['count', 'asc'],
            ['excess', 'asc']
        ])->first();
    }

    /**
     * Generate all possible table combinations that can accommodate the party size
     */
    private function generateCombinations(Collection $tables, int $partySize): Collection
    {
        $combinations = collect();
        $tableCount = $tables->count();
        
        // Try combinations of 2, 3, 4, etc. tables
        for ($i = 2; $i <= min($tableCount, 4); $i++) {
            $combinations = $combinations->merge(
                $this->generateCombinationsOfSize($tables, $i, $partySize)
            );
        }
        
        return $combinations;
    }

    /**
     * Generate combinations of a specific size
     */
    private function generateCombinationsOfSize(Collection $tables, int $size, int $partySize): Collection
    {
        $combinations = collect();
        $tableCount = $tables->count();
        
        // Generate all combinations of the given size
        for ($i = 0; $i <= $tableCount - $size; $i++) {
            for ($j = $i + 1; $j <= $tableCount - $size + 1; $j++) {
                $combination = $tables->slice($i, $size);
                
                if ($this->isValidCombination($combination, $partySize)) {
                    $combinations->push([
                        'tables' => $combination,
                        'count' => $combination->count(),
                        'excess' => $this->calculateExcessCapacity($combination, $partySize),
                    ]);
                }
            }
        }
        
        return $combinations;
    }

    /**
     * Check if a combination of tables can accommodate the party size
     */
    private function isValidCombination(Collection $tables, int $partySize): bool
    {
        $totalCapacity = $tables->sum('capacity');
        return $totalCapacity >= $partySize;
    }

    /**
     * Calculate the excess capacity for a combination of tables
     */
    private function calculateExcessCapacity(Collection $tables, int $partySize): int
    {
        $totalCapacity = $tables->sum('capacity');
        return $totalCapacity - $partySize;
    }

    /**
     * Check if tables can be combined (based on combinable_with rules)
     */
    public function canCombineTables(Collection $tables): bool
    {
        if ($tables->count() <= 1) {
            return true;
        }

        $firstTable = $tables->first();
        $otherTables = $tables->skip(1);

        foreach ($otherTables as $table) {
            if (!$firstTable->canCombineWith($table->type)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the total capacity of a collection of tables
     */
    public function getTotalCapacity(Collection $tables): int
    {
        return $tables->sum('capacity');
    }

    /**
     * Get the excess capacity of a collection of tables for a given party size
     */
    public function getExcessCapacity(Collection $tables, int $partySize): int
    {
        return $this->getTotalCapacity($tables) - $partySize;
    }
}

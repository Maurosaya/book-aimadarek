<?php

namespace App\Domain;

use App\Models\Resource;
use Illuminate\Support\Collection;

/**
 * Table Allocator
 * 
 * Handles optimal table allocation for restaurant bookings
 * Implements algorithms to minimize table usage and capacity waste
 */
class TableAllocator
{
    /**
     * Allocate optimal table combination for party size
     * 
     * @param Collection $availableTables Available tables
     * @param int $partySize Required party size
     * @return Collection Allocated tables
     */
    public function allocateTables(Collection $availableTables, int $partySize): Collection
    {
        if ($availableTables->isEmpty()) {
            return collect();
        }

        // Try to find optimal combination
        $optimalCombination = $this->findOptimalCombination($availableTables, $partySize);
        
        if ($optimalCombination) {
            return $optimalCombination;
        }

        // If no optimal combination found, return empty collection
        return collect();
    }

    /**
     * Find optimal table combination using multiple strategies
     */
    private function findOptimalCombination(Collection $tables, int $partySize): ?Collection
    {
        // Strategy 1: Exact match
        $exactMatch = $this->findExactMatch($tables, $partySize);
        if ($exactMatch) {
            return $exactMatch;
        }

        // Strategy 2: Minimal waste (closest capacity)
        $minimalWaste = $this->findMinimalWasteCombination($tables, $partySize);
        if ($minimalWaste) {
            return $minimalWaste;
        }

        // Strategy 3: Minimal tables (fewest tables needed)
        $minimalTables = $this->findMinimalTablesCombination($tables, $partySize);
        if ($minimalTables) {
            return $minimalTables;
        }

        return null;
    }

    /**
     * Find exact capacity match
     */
    private function findExactMatch(Collection $tables, int $partySize): ?Collection
    {
        $exactTable = $tables->firstWhere('capacity', $partySize);
        return $exactTable ? collect([$exactTable]) : null;
    }

    /**
     * Find combination with minimal capacity waste
     */
    private function findMinimalWasteCombination(Collection $tables, int $partySize): ?Collection
    {
        $bestCombination = null;
        $minWaste = PHP_INT_MAX;

        // Try all possible combinations of 2 tables
        $tableArray = $tables->toArray();
        $count = count($tableArray);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $table1 = $tableArray[$i];
                $table2 = $tableArray[$j];
                $totalCapacity = $table1['capacity'] + $table2['capacity'];

                if ($totalCapacity >= $partySize) {
                    $waste = $totalCapacity - $partySize;
                    if ($waste < $minWaste) {
                        $minWaste = $waste;
                        $bestCombination = collect([$table1, $table2]);
                    }
                }
            }
        }

        // Try all possible combinations of 3 tables
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                for ($k = $j + 1; $k < $count; $k++) {
                    $table1 = $tableArray[$i];
                    $table2 = $tableArray[$j];
                    $table3 = $tableArray[$k];
                    $totalCapacity = $table1['capacity'] + $table2['capacity'] + $table3['capacity'];

                    if ($totalCapacity >= $partySize) {
                        $waste = $totalCapacity - $partySize;
                        if ($waste < $minWaste) {
                            $minWaste = $waste;
                            $bestCombination = collect([$table1, $table2, $table3]);
                        }
                    }
                }
            }
        }

        return $bestCombination;
    }

    /**
     * Find combination using minimal number of tables
     */
    private function findMinimalTablesCombination(Collection $tables, int $partySize): ?Collection
    {
        // Sort tables by capacity (descending) to try largest tables first
        $sortedTables = $tables->sortByDesc('capacity');
        
        // Try single table first
        $singleTable = $sortedTables->first(function ($table) use ($partySize) {
            return $table->capacity >= $partySize;
        });
        
        if ($singleTable) {
            return collect([$singleTable]);
        }

        // Try two tables
        $twoTableCombination = $this->findTwoTableCombination($sortedTables, $partySize);
        if ($twoTableCombination) {
            return $twoTableCombination;
        }

        // Try three tables
        return $this->findThreeTableCombination($sortedTables, $partySize);
    }

    /**
     * Find combination of two tables
     */
    private function findTwoTableCombination(Collection $sortedTables, int $partySize): ?Collection
    {
        $tableArray = $sortedTables->toArray();
        $count = count($tableArray);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $table1 = $tableArray[$i];
                $table2 = $tableArray[$j];
                
                if ($table1['capacity'] + $table2['capacity'] >= $partySize) {
                    return collect([$table1, $table2]);
                }
            }
        }

        return null;
    }

    /**
     * Find combination of three tables
     */
    private function findThreeTableCombination(Collection $sortedTables, int $partySize): ?Collection
    {
        $tableArray = $sortedTables->toArray();
        $count = count($tableArray);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                for ($k = $j + 1; $k < $count; $k++) {
                    $table1 = $tableArray[$i];
                    $table2 = $tableArray[$j];
                    $table3 = $tableArray[$k];
                    
                    if ($table1['capacity'] + $table2['capacity'] + $table3['capacity'] >= $partySize) {
                        return collect([$table1, $table2, $table3]);
                    }
                }
            }
        }

        return null;
    }

    /**
     * Calculate capacity waste for a table combination
     */
    public function calculateWaste(Collection $tables, int $partySize): int
    {
        $totalCapacity = $tables->sum('capacity');
        return max(0, $totalCapacity - $partySize);
    }

    /**
     * Check if tables can be combined (combinable_with logic)
     */
    public function canCombineTables(Collection $tables): bool
    {
        if ($tables->count() <= 1) {
            return true;
        }

        // Check if all tables can be combined with each other
        foreach ($tables as $table1) {
            foreach ($tables as $table2) {
                if ($table1->id !== $table2->id) {
                    if (!$table1->canCombineWith($table2->type)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
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

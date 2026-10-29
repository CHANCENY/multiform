<?php

// This is file is part of simp/multiform php library.

namespace Simp\Multiform\lib\wrapper;

/**
 * Defines application-controlled temporary storage for multi-step form values.
 *
 * Implementations can use sessions, cache, a database, or another store.
 */
interface FormStateSessionStore
{
    /**
     * Loads values saved for a form flow.
     *
     * @param string $key Unique form flow key.
     *
     * @return array Saved values, or an empty array when none exist.
     */
    public function load(string $key): array;

    /**
     * Saves values for a form flow.
     *
     * @param string $key    Unique form flow key.
     * @param array  $values Values to persist.
     *
     * @return void
     */
    public function save(string $key, array $values): void;

    /**
     * Deletes saved values for a form flow.
     *
     * @param string $key Unique form flow key.
     *
     * @return void
     */
    public function clear(string $key): void;
}

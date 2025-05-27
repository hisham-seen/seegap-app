<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace Altum\Controllers\BiolinkBlocks\Interfaces;

defined('ALTUMCODE') || die();

/**
 * Interface for biolink block handlers
 * 
 * This interface defines the contract that all block handlers must implement
 * to ensure consistent behavior across different block type categories.
 */
interface BlockHandlerInterface {
    
    /**
     * Create a new biolink block
     * 
     * @param string $type The block type to create
     * @param array $data The POST data for block creation
     * @return void
     */
    public function create($type);
    
    /**
     * Update an existing biolink block
     * 
     * @param string $type The block type to update
     * @param array $data The POST data for block update
     * @return void
     */
    public function update($type);
    
    /**
     * Validate block data before processing
     * 
     * @param string $type The block type being validated
     * @param array $data The data to validate
     * @return bool True if validation passes
     */
    public function validate($type, $data = []);
    
    /**
     * Get supported block types for this handler
     * 
     * @return array Array of supported block type names
     */
    public function getSupportedTypes();
}

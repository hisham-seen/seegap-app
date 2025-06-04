<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\Controllers\LinkHandlers\Interfaces;

defined('SEEGAP') || die();

/**
 * Interface for link handlers
 * 
 * This interface defines the contract that all link handlers must implement
 * to ensure consistent behavior across different link types.
 */
interface LinkHandlerInterface {
    
    /**
     * Create a new link
     * 
     * @param string $type The link type to create
     * @return void
     */
    public function create($type);
    
    /**
     * Update an existing link
     * 
     * @param string $type The link type to update
     * @return void
     */
    public function update($type);
    
    /**
     * Validate link data before processing
     * 
     * @param string $type The link type being validated
     * @param array $data The data to validate
     * @return bool True if validation passes
     */
    public function validate($type, $data = []);
    
    /**
     * Get supported link types for this handler
     * 
     * @return array Array of supported link type names
     */
    public function getSupportedTypes();
}

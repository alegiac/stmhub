<?php

namespace Application\Constants;

class ItemType
{
	/**
     * Tipo select
     */
	const TYPE_SELECT = 1;
	
	/**
	 * Tipo multi submit (vero-falso, etc)
	 */
    const TYPE_MULTISUBMIT = 2;
    
    /**
     * Tipo riordinamento
     */
    const TYPE_REORDER = 3;
    
    /**
     * Tipo insert
     */
    const TYPE_INSERT = 4;
    
    /**
     * Tipo empty (solo ok)
     */
    const TYPE_EMPTY = 5;
    
}   
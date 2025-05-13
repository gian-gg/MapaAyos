<?php

function calculateResolutionRate($resolved, $active)
{
    if ($active === 0) return 0; // Avoid division by zero
    return round(($resolved / $active) * 100, 2); // Rounded to 2 decimal places
}

<?php

if (!function_exists('format_indian_currency')) {
    /**
     * Format a number in Indian currency format (Lakhs, Crores)
     * 
     * @param float|int $amount The amount to format
     * @param bool $showDecimals Whether to show decimal places for precision
     * @return string Formatted currency string
     */
    function format_indian_currency($amount, $showDecimals = false)
    {
        if ($amount < 0) {
            return '-' . format_indian_currency(abs($amount), $showDecimals);
        }

        // Less than 1 lakh - show exact number with commas
        if ($amount < 100000) {
            return '₹' . number_format($amount, 0);
        }
        
        // Between 1 lakh and 1 crore
        if ($amount < 10000000) {
            $lakhs = $amount / 100000;
            
            // If it's a whole number of lakhs, don't show decimals
            if ($lakhs == floor($lakhs)) {
                return number_format($lakhs, 0) . ' Lakhs';
            }
            
            // Show up to 2 decimal places for precision
            return number_format($lakhs, $showDecimals ? 2 : 1) . ' Lakhs';
        }
        
        // 1 crore and above
        $crores = $amount / 10000000;
        
        // If it's a whole number of crores, don't show decimals
        if ($crores == floor($crores)) {
            return number_format($crores, 0) . ' Cr';
        }
        
        // Show up to 2 decimal places for precision
        return number_format($crores, $showDecimals ? 2 : 1) . ' Cr';
    }
}

if (!function_exists('format_indian_number')) {
    /**
     * Format a number in Indian numbering system with commas (00,00,00,000)
     * 
     * @param float|int $number The number to format
     * @return string Formatted number string
     */
    function format_indian_number($number)
    {
        $number = strval($number);
        $afterPoint = '';
        
        if (strpos($number, '.') !== false) {
            list($number, $afterPoint) = explode('.', $number);
        }
        
        $numberLength = strlen($number);
        
        if ($numberLength <= 3) {
            return $number . ($afterPoint ? '.' . $afterPoint : '');
        }
        
        $lastThree = substr($number, -3);
        $remaining = substr($number, 0, $numberLength - 3);
        
        if ($remaining != '') {
            $lastThree = ',' . $lastThree;
        }
        
        $result = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining) . $lastThree;
        
        return $result . ($afterPoint ? '.' . $afterPoint : '');
    }
}

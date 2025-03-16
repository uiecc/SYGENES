<?php

namespace App\Service;

class AcademicYearService
{
    /**
     * Obtient l'année académique actuelle (ex: "2024-2025")
     */
    public function getCurrentAcademicYear(): string
    {
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');
        
        // Si on est entre janvier et août, on est dans l'année académique qui a commencé l'année précédente
        if ($currentMonth >= 1 && $currentMonth <= 8) {
            return ($currentYear - 1) . '-' . $currentYear;
        }
        
        // Sinon, on est dans l'année académique qui commence cette année
        return $currentYear . '-' . ($currentYear + 1);
    }
}